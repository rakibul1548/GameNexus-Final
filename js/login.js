document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
  
    const email = e.target.email.value;
    const password = e.target.password.value;
  
    // Mock login
    console.log('Logging in:', { email, password });
  
    // You can replace this with backend logic or JWT token call
    alert('Login successful!');
    window.location.href = 'index.html'; // redirect to homepage after login
  });
  