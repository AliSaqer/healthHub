function toggleForm() {
    var role = document.getElementById('role').value;
    if (role === 'patient') {
        document.getElementById('patient-form').style.display = 'block';
        document.getElementById('doctor-form').style.display = 'none';
    } else if (role === 'doctor') {
        document.getElementById('doctor-form').style.display = 'block';
        document.getElementById('patient-form').style.display = 'none';
    } else {
        document.getElementById('patient-form').style.display = 'none';
        document.getElementById('doctor-form').style.display = 'none';
    }
}
document.getElementById('patient').addEventListener('change', function() {
    document.getElementById('patient-form').style.display = 'block';
    document.getElementById('doctor-form').style.display = 'none';
});

document.getElementById('doctor').addEventListener('change', function() {
    document.getElementById('doctor-form').style.display = 'block';
    document.getElementById('patient-form').style.display = 'none';
});

document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const selectedRole = document.querySelector('input[name="role"]:checked').value;

    if (selectedRole === 'doctor') {
        window.location.href = 'doctor_homepage.html';  // Redirect to Doctor's Homepage
    } else if (selectedRole === 'patient') {
        window.location.href = 'patient_homepage.html';  // Redirect to Patient's Homepage
    }
});