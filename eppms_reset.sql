-- ============================================================
-- ePPMS DATABASE RESET SCRIPT
-- Microsoft SQL Server
-- Based on actual codebase migrations
--
-- What this script does:
--   1. Creates a full .bak backup of the database
--   2. Clears all application data
--   3. Preserves all lookup/reference tables
--   4. Re-creates the admin user
--      Email    : dsd@eppms.ng
--      Password : Password1
--
-- HOW TO RUN:
--   Open SQL Server Management Studio (SSMS)
--   Update @DatabaseName and @BackupPath below
--   Click Execute (F5)
--   Run as a login with db_owner or sysadmin role
-- ============================================================


-- ============================================================
-- STEP 0 - UPDATE THESE BEFORE RUNNING
-- ============================================================

USE [eppms]; -- << Change to your actual database name

DECLARE @DatabaseName NVARCHAR(128) = N'eppms';
DECLARE @BackupPath   NVARCHAR(500) = N'C:\Backups\';
-- The backup folder must already exist on the server

DECLARE @BackupFile   NVARCHAR(500);
DECLARE @Timestamp    NVARCHAR(30);
DECLARE @Now          DATETIME2 = GETDATE();

SET @Timestamp  = CONVERT(NVARCHAR, @Now, 112)
    + '_' + REPLACE(CONVERT(NVARCHAR, @Now, 108), ':', '');
SET @BackupFile = @BackupPath + @DatabaseName + '_backup_' + @Timestamp + N'.bak';

PRINT '============================================================';
PRINT ' ePPMS DATABASE RESET';
PRINT '============================================================';
PRINT ' Database : ' + @DatabaseName;
PRINT ' Backup   : ' + @BackupFile;
PRINT ' Time     : ' + CONVERT(NVARCHAR, @Now, 120);
PRINT '';


-- ============================================================
-- STEP 1 - BACKUP THE DATABASE
-- ============================================================

PRINT '[1/8] Backing up database...';

BEGIN TRY
    BACKUP DATABASE [eppms]
    TO DISK = @BackupFile
    WITH
        FORMAT,
        INIT,
        MEDIANAME = N'ePPMS_Reset_Backup',
        NAME      = N'ePPMS Full Backup Before Reset',
        STATS     = 25;

    PRINT '      Backup saved: ' + @BackupFile;
END TRY
BEGIN CATCH
    PRINT '      ERROR: Backup failed - ' + ERROR_MESSAGE();
    PRINT '      The reset will NOT continue.';
    PRINT '      Fix the backup path and try again.';
    THROW;
END CATCH

PRINT '';


-- ============================================================
-- STEP 2 - DISABLE ALL FOREIGN KEY CONSTRAINTS
-- ============================================================

PRINT '[2/8] Disabling foreign key constraints...';
EXEC sp_MSforeachtable 'ALTER TABLE ? NOCHECK CONSTRAINT ALL';
PRINT '      Done.';
PRINT '';


-- ============================================================
-- STEP 3 - CLEAR ALL APPLICATION DATA
-- Order: children before parents
-- ============================================================

PRINT '[3/8] Clearing application data...';

-- Chat messages
IF OBJECT_ID('tasks_chat_messages',      'U') IS NOT NULL DELETE FROM [tasks_chat_messages];
IF OBJECT_ID('milestones_chat_messages', 'U') IS NOT NULL DELETE FROM [milestones_chat_messages];
IF OBJECT_ID('project_chats',            'U') IS NOT NULL DELETE FROM [project_chats];
PRINT '      Cleared: chat messages';

-- Images
IF OBJECT_ID('task_images',      'U') IS NOT NULL DELETE FROM [task_images];
IF OBJECT_ID('milestone_images', 'U') IS NOT NULL DELETE FROM [milestone_images];
PRINT '      Cleared: images';

-- Tasks and Milestones
IF OBJECT_ID('tasks',      'U') IS NOT NULL DELETE FROM [tasks];
IF OBJECT_ID('milestones', 'U') IS NOT NULL DELETE FROM [milestones];
PRINT '      Cleared: tasks and milestones';

-- Project data
IF OBJECT_ID('project_personnels', 'U') IS NOT NULL DELETE FROM [project_personnels];
IF OBJECT_ID('projects',           'U') IS NOT NULL DELETE FROM [projects];
PRINT '      Cleared: projects and project personnels';

-- Firm data
IF OBJECT_ID('contractor_personnels', 'U') IS NOT NULL DELETE FROM [contractor_personnels];
IF OBJECT_ID('contractors',           'U') IS NOT NULL DELETE FROM [contractors];
PRINT '      Cleared: contractors and contractor personnels';

-- Personnel
IF OBJECT_ID('personnels', 'U') IS NOT NULL DELETE FROM [personnels];
PRINT '      Cleared: personnels';

-- Organisation structure
IF OBJECT_ID('units',        'U') IS NOT NULL DELETE FROM [units];
IF OBJECT_ID('departments',  'U') IS NOT NULL DELETE FROM [departments];
IF OBJECT_ID('directorates', 'U') IS NOT NULL DELETE FROM [directorates];
IF OBJECT_ID('offices',      'U') IS NOT NULL DELETE FROM [offices];
IF OBJECT_ID('organizations','U') IS NOT NULL DELETE FROM [organizations];
PRINT '      Cleared: organisation structure';

-- System tables
IF OBJECT_ID('notifications',       'U') IS NOT NULL DELETE FROM [notifications];
IF OBJECT_ID('jobs',                'U') IS NOT NULL DELETE FROM [jobs];
IF OBJECT_ID('job_batches',         'U') IS NOT NULL DELETE FROM [job_batches];
IF OBJECT_ID('failed_jobs',         'U') IS NOT NULL DELETE FROM [failed_jobs];
IF OBJECT_ID('telescope_entries',   'U') IS NOT NULL DELETE FROM [telescope_entries];
IF OBJECT_ID('telescope_entries_tags','U') IS NOT NULL DELETE FROM [telescope_entries_tags];
IF OBJECT_ID('telescope_monitoring','U') IS NOT NULL DELETE FROM [telescope_monitoring];
IF OBJECT_ID('cache',               'U') IS NOT NULL DELETE FROM [cache];
IF OBJECT_ID('cache_locks',         'U') IS NOT NULL DELETE FROM [cache_locks];
IF OBJECT_ID('sessions',            'U') IS NOT NULL DELETE FROM [sessions];
IF OBJECT_ID('password_reset_tokens','U') IS NOT NULL DELETE FROM [password_reset_tokens];
IF OBJECT_ID('administration_tools','U') IS NOT NULL DELETE FROM [administration_tools];
PRINT '      Cleared: system tables (jobs, cache, sessions, telescope)';

PRINT '';


-- ============================================================
-- STEP 4 - CLEAR USERS AND ROLE ASSIGNMENTS
-- ============================================================

PRINT '[4/8] Clearing users and role assignments...';

IF OBJECT_ID('model_has_roles',       'U') IS NOT NULL DELETE FROM [model_has_roles];
IF OBJECT_ID('model_has_permissions', 'U') IS NOT NULL DELETE FROM [model_has_permissions];
IF OBJECT_ID('users',                 'U') IS NOT NULL DELETE FROM [users];

PRINT '      Cleared: users, model_has_roles, model_has_permissions';
PRINT '';


-- ============================================================
-- STEP 5 - CONFIRM PRESERVED TABLES
-- ============================================================

PRINT '[5/8] Preserved (NOT cleared):';
PRINT '      roles, permissions, role_has_permissions';
PRINT '      role_categories, states, lgas';
PRINT '      office_types, firm_types, project_types';
PRINT '      regions, migrations, settings';
PRINT '';


-- ============================================================
-- STEP 6 - RESET IDENTITY COLUMNS
-- ============================================================

PRINT '[6/8] Resetting identity columns...';

DECLARE @tbl NVARCHAR(128);
DECLARE id_cur CURSOR LOCAL FAST_FORWARD FOR
    SELECT t.name
    FROM   sys.tables  t
    JOIN   sys.columns c ON t.object_id = c.object_id
    WHERE  c.is_identity = 1
      AND  t.name NOT IN (
               'roles','permissions','role_has_permissions',
               'role_categories','states','lgas',
               'office_types','firm_types','project_types',
               'regions','migrations','settings'
           );

OPEN id_cur;
FETCH NEXT FROM id_cur INTO @tbl;
WHILE @@FETCH_STATUS = 0
BEGIN
    BEGIN TRY
        DBCC CHECKIDENT(@tbl, RESEED, 0) WITH NO_INFOMSGS;
    END TRY
    BEGIN CATCH
        -- Some tables may not support reseed - skip silently
    END CATCH
    FETCH NEXT FROM id_cur INTO @tbl;
END
CLOSE id_cur;
DEALLOCATE id_cur;

PRINT '      Done.';
PRINT '';


-- ============================================================
-- STEP 7 - RE-CREATE ADMIN USER
--
-- Users table columns (from actual migration):
--   id (bigint identity), ulid (nvarchar 26 unique),
--   name, email, first_name, last_name, other_name,
--   phone, email_verified_at, verification_token,
--   password, remember_token, deleted_at,
--   created_at, updated_at
-- ============================================================

PRINT '[7/8] Creating admin user...';

DECLARE @AdminRoleId BIGINT;
DECLARE @AdminUserId BIGINT;

SELECT @AdminRoleId = [id]
FROM   [roles]
WHERE  [name]       = N'admin'
  AND  [guard_name] = N'web';

IF @AdminRoleId IS NULL
BEGIN
    PRINT '      WARNING: admin role not found in roles table.';
    PRINT '      Run after script: php artisan db:seed --class=RolesAndPermissionsSeeder';
    PRINT '      Then re-run this step manually.';
END
ELSE
BEGIN
    INSERT INTO [users] (
        [ulid],
        [name],
        [email],
        [first_name],
        [last_name],
        [email_verified_at],
        [password],
        [created_at],
        [updated_at]
    )
    VALUES (
        N'01JEPPMSADMIN00000000000001',
        N'DSD EPPMS',
        N'dsd@eppms.ng',
        N'DSD',
        N'EPPMS',
        @Now,
        -- bcrypt hash of "Password1" cost 12 (Laravel compatible)
        N'$2y$12$h7TpYSqeMSocqHRlqaBVfOlcKHR6oCJjdSmIPMXq2g6VbELWGS4t2',
        @Now,
        @Now
    );

    SET @AdminUserId = SCOPE_IDENTITY();

    INSERT INTO [model_has_roles] ([role_id], [model_type], [model_id])
    VALUES (@AdminRoleId, N'App\Models\User', @AdminUserId);

    PRINT '      Admin user created successfully.';
    PRINT '        User ID  : ' + CAST(@AdminUserId AS NVARCHAR);
    PRINT '        Email    : dsd@eppms.ng';
    PRINT '        Password : Password1';
    PRINT '        Role     : admin';
END

PRINT '';


-- ============================================================
-- STEP 8 - RE-ENABLE FOREIGN KEY CONSTRAINTS
-- ============================================================

PRINT '[8/8] Re-enabling foreign key constraints...';
EXEC sp_MSforeachtable 'ALTER TABLE ? WITH CHECK CHECK CONSTRAINT ALL';
PRINT '      Done.';
PRINT '';


-- ============================================================
-- COMPLETE
-- ============================================================

PRINT '============================================================';
PRINT ' RESET COMPLETE';
PRINT '============================================================';
PRINT ' Backup    : ' + @BackupFile;
PRINT ' Login URL : https://eppms.ncaa.gov.ng/login';
PRINT ' Email     : dsd@eppms.ng';
PRINT ' Password  : Password1';
PRINT '============================================================';
PRINT '';
PRINT ' NEXT STEPS AFTER LOGGING IN:';
PRINT ' 1. Organisation > Organisation Registration > set name/logo';
PRINT ' 2. Organisation > Office Location > add regional offices';
PRINT ' 3. Organisation > Directorates > add directorates';
PRINT ' 4. Organisation > Department > add departments';
PRINT ' 5. Organisation > Personnels > add staff accounts';
PRINT ' 6. Firm > Firms > add contractor/consultant firms';
PRINT ' 7. Change the admin password in Settings';
PRINT '============================================================';
PRINT '';
PRINT ' IF PASSWORD DOES NOT WORK:';
PRINT ' Open Command Prompt on the IIS server and run:';
PRINT '   cd C:\inetpub\wwwroot\Eppms';
PRINT '   php artisan tinker';
PRINT '   (then paste these two lines and press Enter):';
PRINT '   $u = App\Models\User::where("email","dsd@eppms.ng")->first();';
PRINT '   $u->password = Hash::make("Password1"); $u->save(); exit;';
PRINT '============================================================';
