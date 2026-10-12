<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-bs-theme="light"
    data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/png" href="{{ asset('/assets/images/logos/favicon.png') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    @yield('custom-css')
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @php
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    @endphp

</head>

<body class="link-sidebar">
    <!-- Toast -->
    <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1">Welcome to EPPMS</h5>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none"
                data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <div class="preloader">
        <img src="{{ asset('assets/images/logos/favicon.png') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper">
        <aside class="left-sidebar with-vertical">
            <div>
                <div class="brand-logo d-flex align-items-center px-3" style="background-color:#01054C;">
                    <a href="/" class="text-nowrap logo-img">
                        <img src="{{ asset('assets/images/logos/ncaa-icon.png') }}" alt="Logo" />
                    </a>
                </div>

                <nav class="sidebar-nav scroll-sidebar" data-simplebar style="background-color:#F1F2FF;">
                    <ul class="sidebar-menu" id="sidebarnav">
                        <li>
                            <span class="sidebar-divider lg"></span>
                        </li>

                        <li class="sidebar-item">
                            <a href="{{ route('home') }}" class="sidebar-link">
                                <i class="ti ti-aperture"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>

                        @hasanyrole('admin|personnel|directorate_admin|head_of_department|organization_admin')
                            <li class="sidebar-item">

                                <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                    <span class="hide-menu">Organization</span>
                                </a>

                                <ul aria-expanded="false" class="collapse first-level">
                                    @role('admin')
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('organizations.create') }}">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu">Organization Registration</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('offices.index') }}">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu">Office Location</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('directorates.index') }}">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu">Directorates</span>
                                            </a>
                                        </li>
                                    @endrole

                                    @hasanyrole('admin|personnel|directorate_admin|organization_admin')
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('departments.index') }}">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu">Department</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('units.index') }}">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu">Unit</span>
                                            </a>
                                        </li>
                                    @endrole

                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('personnels.index') }}">
                                            <span class="icon-small"></span>
                                            <span class="hide-menu">Personnels</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endrole



                        <!-- Contractor -->
                        @hasanyrole('admin|personnel|contractor')
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                    <span class="hide-menu">Firm</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    @hasanyrole('admin|personnel')
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('contractors.index') }}">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu">Firms</span>
                                            </a>
                                        </li>
                                    @endrole

                                    @role('contractor|consultant|admin')
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="{{ route('contractor-personnels.index') }}">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu">Contractor Personnel</span>
                                            </a>
                                        </li>
                                    @endrole

                                </ul>
                            </li>
                        @endrole

                        <!-- Project -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="hide-menu">Project</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('projects.index') }}">
                                        <span class="icon-small"></span>
                                        <span class="hide-menu">Projects</span>
                                    </a>
                                </li>
                                @hasanyrole('admin|personnel')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('projects.create') }}">
                                            <span class="icon-small"></span>
                                            <span class="hide-menu">Add New Project</span>
                                        </a>
                                    </li>
                                @endrole
                            </ul>
                        </li>
                        <!-- End of Project -->

                        @hasanyrole('admin')

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                    <span class="hide-menu">Settings</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('roles.index') }}">
                                            <span class="icon-small"></span>
                                            <span class="hide-menu">Roles</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('roles.create') }}">
                                            <span class="icon-small"></span>
                                            <span class="hide-menu">Create Role</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                            <span class="hide-menu">Users</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            <li class="sidebar-item">
                                                <a class="sidebar-link" href="{{ route('admin.users.index') }}">
                                                    <span class="icon-small"></span>
                                                    <span class="hide-menu">Users</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a class="sidebar-link" href="{{ route('admin.users.create') }}">
                                                    <span class="icon-small"></span>
                                                    <span class="hide-menu">Create User</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('settings.index') }}"
                                            aria-expanded="false">
                                            <span class="hide-menu">Settings</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        @endrole

                        <li>
                            <span class="sidebar-divider lg"></span>
                        </li>
                        <!-- LogOut -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ti ti-logout"></i>
                                <span class="hide-menu">Logout</span>
                            </a>
                        </li>
                        <!-- End of LogOut -->
                    </ul>
                </nav>

            </div>
        </aside>
        <div class="page-wrapper">
            <header class="topbar" style="background-color:#01054C;">
                <div class="with-vertical">
                    <nav class="navbar navbar-expand-lg p-0">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link nav-icon-hover-bg rounded-circle sidebartoggler d-flex d-xl-none"
                                    id="headerCollapse" href="javascript:void(0)">
                                    <iconify-icon icon="solar:hamburger-menu-line-duotone"
                                        class="fs-6"></iconify-icon>
                                </a>
                            </li>
                            <li class="nav-item d-none d-xl-flex nav-icon-hover-bg rounded-circle">
                                <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    <iconify-icon icon="solar:magnifer-linear" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                            <li class="nav-item d-none d-lg-flex dropdown nav-icon-hover-bg rounded-circle">
                                <div class="hover-dd">
                                    <a class="nav-link" id="drop2" href="#" aria-haspopup="true"
                                        aria-expanded="false">
                                        <iconify-icon icon="solar:widget-3-line-duotone"
                                            class="fs-6"></iconify-icon>
                                    </a>
                                </div>
                            </li>
                        </ul>

                        <div class="d-block d-lg-none py-9 py-xl-0">
                            <img src="{{ asset('assets/images/logos/ncaa-icon.png') }}" alt="img" />
                        </div>
                        <a class="navbar-toggler p-0 border-0 nav-icon-hover-bg rounded-circle"
                            href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <iconify-icon icon="solar:menu-dots-bold-duotone" class="fs-6"></iconify-icon>
                        </a>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <div class="d-flex align-items-center justify-content-between">
                                <ul
                                    class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
                                    <li class="nav-item d-block d-xl-none">
                                        <a class="nav-link nav-icon-hover-bg rounded-circle" href="javascript:void(0)"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <iconify-icon icon="solar:magnifer-line-duotone"
                                                class="fs-6"></iconify-icon>
                                        </a>
                                    </li>


                                    @php
                                        $unreadNotifications = auth()->user()->unreadNotifications->take(3);
                                    @endphp


                                    <li class="nav-item dropdown nav-icon-hover-bg rounded-circle">
                                        <a class="nav-link position-relative" href="javascript:void(0)"
                                            id="drop2" aria-expanded="false">
                                            <iconify-icon icon="solar:bell-bing-line-duotone"
                                                class="fs-6"></iconify-icon>
                                        </a>

                                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                            aria-labelledby="drop2">
                                            <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                                <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                                                    {{ auth()->user()->unreadNotifications->count() ?? 0 }}</span>
                                            </div>
                                            <div class="message-body" data-simplebar>

                                                @if ($unreadNotifications->count() > 0)
                                                    @foreach ($unreadNotifications as $key => $notification)
                                                        <a href="{{ isset($notification->data['url']) ? url($notification->data['url']) : '#' }}"
                                                            class="py-6 px-7 d-flex align-items-center dropdown-item gap-3 mark-as-read"
                                                            data-id="{{ $notification->id }}">
                                                            <span
                                                                class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                                                                <iconify-icon
                                                                    icon="solar:widget-3-line-duotone"></iconify-icon>
                                                            </span>
                                                            <div class="w-75 d-inline-block ">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <h6 class="mb-1 fw-semibold">
                                                                        {{ $notification->data['title'] ?? 'Notification - ' . $key + 1 }}
                                                                    </h6>
                                                                    <span
                                                                        class="d-block fs-2">{{ $notification->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <span
                                                                    class="d-block text-truncate text-truncate fs-11">{{ $notification->data['message'] }}</span>
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                @else
                                                    <a href="javascript:void(0)"
                                                        class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                                        <span
                                                            class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                                                            <iconify-icon
                                                                icon="solar:widget-3-line-duotone"></iconify-icon>
                                                        </span>
                                                        <div class="w-75 d-inline-block ">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                <h6 class="mb-1 fw-semibold">No Notifications</h6>
                                                                <span class="d-block fs-2">{{ date('H:i:s') }}</span>
                                                            </div>
                                                            <span class="d-block text-truncate text-truncate fs-11">No
                                                                new
                                                                notifications</span>
                                                        </div>
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="py-6 px-7 mb-1">
                                                <a href="{{ route('notifications.index') }}"
                                                    class="btn btn-sm btn-primary w-100">View All Notifications</a>
                                            </div>

                                        </div>
                                    </li>



                                    <li class="nav-item dropdown">
                                        <a class="nav-link" href="javascript:void(0)" id="drop1"
                                            aria-expanded="false">
                                            <div class="d-flex align-items-center gap-2 lh-base">
                                                <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
                                                    class="rounded-circle" width="35" height="35"
                                                    alt="img" />

                                                <div>
                                                    <h5 class="mb-0 fs-12"> {{ auth()->user()->name }}</h5>
                                                </div>
                                                <iconify-icon icon="solar:alt-arrow-down-bold"
                                                    class="fs-2"></iconify-icon>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu profile-dropdown dropdown-menu-end dropdown-menu-animate-up"
                                            aria-labelledby="drop1">
                                            <div class="position-relative px-4 pt-3 pb-2">
                                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom gap-6">
                                                    <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
                                                        class="rounded-circle" width="56" height="56"
                                                        alt="img" />
                                                    <div>
                                                        <h5 class="mb-0 fs-12">{{ auth()->user()->name }}</h5>
                                                        <p class="mb-0 text-dark"> {{ auth()->user()->email }} </p>
                                                    </div>
                                                </div>
                                                <div class="message-body">
                                                    <a href="javascript:void(0)"
                                                        class="p-2 dropdown-item h6 rounded-1">
                                                        My Profile
                                                    </a>
                                                    <a class="p-2 dropdown-item h6 rounded-1"
                                                        href="{{ route('logout') }}"
                                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                        Sign Out
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </header>
            <main>
                @yield('content')
            </main>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <div class="dark-transparent sidebartoggler"></div>
            <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
            <script src="{{ asset('assets/js/theme/app.init.js') }}"></script>
            <script src="{{ asset('assets/js/theme/theme.js') }}"></script>
            <script src="{{ asset('assets/js/theme/app.min.js') }}"></script>
            <script src="{{ asset('assets/js/theme/sidebarmenu-default.js') }}"></script>

            <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
            {{-- <script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script> --}}
            <script src="{{ asset('assets/libs/fullcalendar/index.global.min.js') }}"></script>

            {{-- <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script> --}}
            <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
            {{-- <script src="{{ asset('assets/js/forms/repeater-init.js') }}"></script> --}}
            {{-- <script src="{{ asset('assets/js/forms/form-wizard.js') }}"></script> --}}
            @yield('custom-script')
</body>

</html>
