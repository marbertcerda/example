// controllers/authController.js
const bcrypt = require('bcrypt');
const saltRounds = 10;
const db = require('../db');

const authController = {
    register: (req, res) => {
        const { username, password } = req.body;

        bcrypt.hash(password, saltRounds, (err, hash) => {
            if (err) throw err;

            db.query('INSERT INTO users (username, password) VALUES (?, ?)', [username, hash], (err, result) => {
                if (err) throw err;
                res.redirect('/');
            });
        });
    },

    login: (req, res) => {
        const { username, password } = req.body;

        db.query('SELECT * FROM users WHERE username = ?', [username], (err, result) => {
            if (err) throw err;

            if (result.length > 0) {
                bcrypt.compare(password, result[0].password, (err, match) => {
                    if (err) throw err;

                    if (match) {
                        req.session.user = username;
                        res.redirect('/dashboard');
                    } else {
                        res.send('Incorrect password');
                    }
                });
            } else {
                res.send('User does not exist');
            }
        });
    },

    logout: (req, res) => {
        req.session.destroy((err) => {
            if (err) throw err;
            res.redirect('/');
        });
    },
};

module.exports = authController;
