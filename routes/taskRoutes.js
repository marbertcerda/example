// routes/taskRoutes.js
const express = require('express');
const router = express.Router();
const taskController = require('../controllers/taskController');

// Define routes
router.get('/', taskController.getAllTasks);
router.post('/add', taskController.addTask);
router.post('/update/:id', taskController.updateTask);
router.post('/delete/:id', taskController.deleteTask);
router.get('/edit/:id', taskController.editTaskPage);
router.post('/edit/:id', taskController.editTask);

// Export the router
module.exports = router;
