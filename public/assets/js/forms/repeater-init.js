$(function () {
    "use strict";

    // Default
    $(".repeater-default").repeater();

    // Custom Show / Hide Configurations
    $(".file-repeater, .email-repeater").repeater({
      show: function () {
        $(this).slideDown();
      },
      hide: function (remove) {
        if (confirm("Are you sure you want to remove this item?")) {
          $(this).slideUp(remove);
        }
      },
    });
  });

  var room = 1;

  function education_fields() {
    room++;
    var objTo = document.getElementById("education_fields");
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "mb-3 removeclass" + room);
    var rdiv = "removeclass" + room;
    divtest.innerHTML =
      '<form class="row"><div class="col-sm-3"><div class="form-group"><select class="form-select" id="staff" name="staff"><option>Selected Staff</option><option value="Yusuf">Yusuf</option><option value="SaifurRahman">SaifurRahman</option><option value="Adam">Adam</option><option value="Raph">Raph</option></select></div></div><div class="col-sm-3"><div class="form-group"><select class="form-select" id="role" name="role"><option>Role</option><option value="Manager">Manager</option><option value="P.M.">P.M.</option></select></div></div><div class="col-sm-2"> <div class="form-group"> <button class="btn btn-danger" type="button" onclick="remove_education_fields(' +
      room +
      ');"> <i class="ti ti-minus"></i> </button> </div></div></form>';

    objTo.appendChild(divtest);
  }

  function remove_education_fields(rid) {
    $(".removeclass" + rid).remove();
  }


  var rm =1;

  function contract_person() {
    rm++;
    var objT = document.getElementById("contract_person");
    var divt = document.createElement("div");
    divt.setAttribute("class", "mb-3 rmvclass" + rm);
    var rdiv = "rmvclass" + rm;
    divt.innerHTML =
      '<form class="row"><div class="col-sm-3"><div class="form-group"><select class="form-select" id="staff" name="staff"><option>Selected Staff</option><option value="Yusuf">Yusuf</option><option value="SaifurRahman">SaifurRahman</option><option value="Adam">Adam</option><option value="Raph">Raph</option></select></div></div><div class="col-sm-2"> <div class="form-group"> <button class="btn btn-danger" type="button" onclick="remove_contract_person(' +
      rm +
      ');"> <i class="ti ti-minus"></i> </button> </div></div></form>';

    objT.appendChild(divt);
  }

  function remove_contract_person(out) {
    $(".rmvclass" + out).remove();
  }
