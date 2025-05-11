document.getElementById('signupForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const username = e.target.username.value;
  const email = e.target.email.value;
  const password = e.target.password.value;
  const confirmPassword = e.target.confirmPassword.value;

  if (password !== confirmPassword) {
    alert('Passwords do not match!');
    return;
  }

  // Mock signup
  console.log('Signing up:', { username, email, password });

  alert('Account created successfully!');
  window.location.href = 'login.html'; // Redirect to login page
});
