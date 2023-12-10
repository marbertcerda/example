const express = require('express');
const session = require('express-session');
const authRoutes = require('./routes/authRoutes');
const profileRoutes = require('./routes/profileRoutes');
const profileController = require('./controllers/profileController');
const db = require('./db');

const app = express();

app.set('view engine', 'ejs');
app.use(express.urlencoded({ extended: true }));
app.use(session({
    secret: 'secret',
    resave: true,
    saveUninitialized: true
}));

app.use('/', authRoutes);
app.use('/', profileRoutes);

app.get('/', (req, res) => {
    res.render('index.ejs');
});
app.get('/dashboard', profileController.getDashboard);
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
