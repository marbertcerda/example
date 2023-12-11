// controllers/taskController.js
const mysql = require('mysql');

// MySQL Connection
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'todo_list_db',
});

db.connect((err) => {
  if (err) {
    console.error('Error connecting to MySQL:', err);
    throw err;
  }
  console.log('Connected to MySQL');
});

// Controller methods
exports.getAllTasks = (req, res) => {
  db.query('SELECT * FROM tasks', (err, results) => {
    if (err) {
      console.error('Error fetching tasks from MySQL:', err);
      res.status(500).send('Internal Server Error');
      return;
    }
    res.render('index1', { tasks: results });
  });
};

exports.addTask = (req, res) => {
  const task = req.body.task;
  db.query('INSERT INTO tasks (task) VALUES (?)', [task], (err) => {
    if (err) {
      console.error('Error adding task to MySQL:', err);
      res.status(500).send('Internal Server Error');
      return;
    }
    res.redirect('/');
  });
};

exports.updateTask = (req, res) => {
  const taskId = req.params.id;
  db.query('UPDATE tasks SET completed = NOT completed WHERE id = ?', [taskId], (err) => {
    if (err) {
      console.error('Error updating task in MySQL:', err);
      res.status(500).send('Internal Server Error');
      return;
    }
    res.redirect('/');
  });
};

exports.deleteTask = (req, res) => {
  const taskId = req.params.id;
  db.query('DELETE FROM tasks WHERE id = ?', [taskId], (err) => {
    if (err) {
      console.error('Error deleting task from MySQL:', err);
      res.status(500).send('Internal Server Error');
      return;
    }
    res.redirect('/');
  });
};

exports.editTaskPage = (req, res) => {
  const taskId = req.params.id;
  db.query('SELECT * FROM tasks WHERE id = ?', [taskId], (err, result) => {
    if (err) {
      console.error('Error fetching task from MySQL:', err);
      res.status(500).send('Internal Server Error');
      return;
    }

    if (result.length === 0) {
      res.status(404).send('Task not found');
      return;
    }

    res.render('edit', { task: result[0] });
  });
};

exports.editTask = (req, res) => {
  const taskId = req.params.id;
  const updatedTask = req.body.task;

  db.query('UPDATE tasks SET task = ? WHERE id = ?', [updatedTask, taskId], (err) => {
    if (err) {
      console.error('Error updating task in MySQL:', err);
      res.status(500).send('Internal Server Error');
      return;
    }

    res.redirect('/');
  });
};
