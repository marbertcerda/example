const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');

router.get('/', (req, res) => {
    const { error } = req.query;
    res.render('login', { error });
});

router.post('/login', authController.login);

router.get('/admin-dashboard', authController.isAdmin, (req, res) => {
    res.render('admin-dashboard', { user: req.session.user });
});

router.get('/user-dashboard', authController.isUser, (req, res) => {
    res.render('user-dashboard', { user: req.session.user });
});

module.exports = router;
