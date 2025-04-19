$(document).ready(function () {
  //first thing
  //cancel appointment
  $(".cancel-btn").click(function (e) {
    e.preventDefault();

    const btn = $(this);
    const appointmentId = btn.data("id");
    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this appointment!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: "../actions/cancel_appointment.php",
          method: "POST",
          data: { id: appointmentId },
          dataType: "json",
          success: function (response) {
            if (response === true) {
              // remove row
              btn.closest("tr").remove();
            } else {
              alert("Failed to cancel appointment.");
            }
          },
          error: function () {
            alert("Server error. Try again later.");
          },
        });

        swal("you canceled your appointment", {
          icon: "success",
        });
      } else {
        swal("Your appointment still on!");
      }
    });
  });

  //confirm appointment

  $(".confirm-btn").on("click", function () {
    const appointmentId = $(this).data("id");
    const $row = $(this).closest("tr");

    $.ajax({
      url: "../actions/confirm_appointment.php",
      type: "POST",
      data: { id: appointmentId },
      dataType: "json", // tells jQuery to expect JSON
      success: function (response) {
        if (response === true) {
          location.reload();
        } else {
          alert("Failed to confirm appointment.");
        }
      },
      error: function (xhr, status, error) {
        console.log("XHR:", xhr.responseText);
        alert("Error with AJAX request.");
      },
    });
  });
  //end of confirmation

  //fitching doctor by id
  $("#specialty").on("change", function () {
    var specialtyId = $(this).val();

    if (specialtyId) {
      $.ajax({
        url: "../actions/get_doctors_by_specialty.php",
        method: "POST",
        data: { specialty: specialtyId },
        dataType: "json",
        success: function (data) {
          $("#doctor")
            .empty()
            .append('<option value="">-- Select Doctor --</option>');
          $.each(data, function (index, doctor) {
            $("#doctor").append(
              '<option value="' + doctor.id + '">' + doctor.name + "</option>"
            );
          });
          $("#doctor-section").show();
        },
        error: function () {
          alert("Failed to load doctors.");
        },
      });
    } else {
      $("#doctor").empty();
      $("#doctor-section").hide();
    }
  });
  //end of fetching doctor by id
});
