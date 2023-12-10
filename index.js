// app.js
const express = require('express');
const taskRoutes = require('./routes/taskRoutes'); // Import the route
const app = express();

// EJS Setup
app.set('view engine', 'ejs');

// Middleware
app.use(express.urlencoded({ extended: true }));

// Use the route
app.use('/', taskRoutes);

// Server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Server is running on http://localhost:${PORT}`);
});
