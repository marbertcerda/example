const express = require('express');
const session = require('express-session');
const mysql = require('mysql');
const bcrypt = require('bcrypt');
const bodyParser = require('body-parser');
const path = require('path');

const app = express();
const port = 3000;
app.use(express.static(path.join(__dirname, 'public')));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

app.use(session({
  secret: 'your-secret-key',
  resave: true,
  saveUninitialized: true
}));

const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'sampleshit'
});

db.connect(err => {
  if (err) {
    console.log(err);
  } else {
    console.log('Connected to MySQL database');
  }
});

app.get('/', (req, res) => {
  res.render('login.ejs');
});
app.post('/login', async (req, res) => {
    const { username, password } = req.body;
  
    const query = 'SELECT * FROM users WHERE username = ?';
    db.query(query, [username], async (err, results) => {
      if (err) throw err;
  
      if (results.length > 0) {
        const user = results[0];
  
        const match = await bcrypt.compare(password, user.password);
  
        if (match) {
          req.session.user = user;
  
          if (user.isAdmin) {
            res.redirect('/admin-dashboard');
          } else {
            res.redirect('/user-dashboard');
          }
        } else {
          res.send('Invalid password');
        }
      } else {
        res.send('User not found');
      }
    });
  });
  

  app.get('/admin-dashboard', (req, res) => {
    console.log(req.session.user); 
  
    if (req.session.user && req.session.user.isAdmin) {
      // Fetch records from the database
      const query = 'SELECT * FROM crud_data'; 
      db.query(query, (err, records) => {
        if (err) {
          console.error(err);
          res.status(500).send('Internal Server Error');
        } else {
          console.log(records); 
          res.render('admin-dashboard.ejs', { user: req.session.user, records });
        }
      });
    } else {
      res.redirect('/');
    }
  });
  
  

  app.get('/user-dashboard', (req, res) => {
    if (req.session.user && !req.session.user.isAdmin) {
      const query = 'SELECT * FROM crud_data';
      db.query(query, (err, records) => {
        if (err) {
          console.error(err);
          res.status(500).send('Internal Server Error');
        } else {
          console.log(records); 
          res.render('user-dashboard.ejs', { user: req.session.user, records });
        }
      });
    } else {
      res.redirect('/');
    }
  });
  
app.get('/register', (req, res) => {
    res.render('register.ejs');
  });
  
  app.post('/register', async (req, res) => {
    const { username, password, isAdmin } = req.body;
  
    const isAdminValue = isAdmin === '1';
  
    const hashedPassword = await bcrypt.hash(password, 10);
  
    const query = 'INSERT INTO users (username, password, isAdmin) VALUES (?, ?, ?)';
    db.query(query, [username, hashedPassword, isAdminValue], (err) => {
      if (err) {
        console.error(err);
        res.send('Registration failed. Please try again.');
      } else {
        res.redirect('/');
      }
    });
  });
  

  

app.get('/api/records', (req, res) => {
  const query = 'SELECT * crud_data'; 
  db.query(query, (err, results) => {
    if (err) {
      console.error(err);
      res.status(500).json({ error: 'Internal Server Error' });
    } else {
      res.json(results);
    }
  });
});

  
app.post('/create-record', (req, res) => {
  if (req.session.user && req.session.user.isAdmin) {
    const { name, address, age } = req.body;

    const query = 'INSERT INTO crud_data (name, address, age) VALUES (?, ?, ?)';
    db.query(query, [name, address, age], (err) => {
      if (err) {
        console.error(err);
        res.status(500).send('Internal Server Error');
      } else {
        res.redirect('/admin-dashboard');
      }
    });
  } else {
    res.redirect('/');
  }
});
app.post('/delete-record', (req, res) => {
  console.log('Received POST request to /delete-record');
  if (req.session.user && req.session.user.isAdmin) {
    const recordId = req.body.recordId;

    const query = 'DELETE FROM crud_data WHERE id = ?';
    db.query(query, [recordId], (err) => {
      if (err) {
        console.error(err);
        res.status(500).send('Internal Server Error');
      } else {
        res.redirect('/admin-dashboard');
      }
    });
  } else {
    res.redirect('/');
  }
});

  app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
  });