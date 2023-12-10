// controllers/profileController.js
const bcrypt = require('bcrypt');
const saltRounds = 10;
const db = require('../db');

// controllers/profileController.js
const profileController = {
    getUpdateProfile: (req, res) => {
        if (req.session.user) {
            console.log('User Object:', req.session.user);
            res.render('update-profile.ejs', { user: req.session.user });
        } else {
            res.redirect('/login');
        }
    },
    getDashboard: (req, res) => {
        console.log('Session Object:', req.session);
        if (req.session.user) {
            const userData = req.session.user;
            res.render('dashboard.ejs', { user: userData });
        } else {
            res.redirect('/login');
        }
    },    

    postUpdateProfile: (req, res) => {
        const { currentUsername, newUsername, newPassword, newAge, newAddress } = req.body;

        bcrypt.hash(newPassword, saltRounds, (err, hash) => {
            if (err) throw err;

            db.query(
                'UPDATE users SET username = ?, password = ?, age = ?, address = ? WHERE username = ?',
                [newUsername, hash, newAge, newAddress, currentUsername],
                (err, result) => {
                    if (err) throw err;

                    // Fetch the updated user information from the database
                    db.query('SELECT * FROM users WHERE username = ?', [newUsername], (err, updatedUser) => {
                        if (err) throw err;

                        // Log the updated user information
                        console.log('Updated User Information:', updatedUser[0]);

                        // Update the session with the new user information
                        req.session.user = updatedUser[0];

                        // Redirect to the dashboard after updating the profile
                        res.redirect('/dashboard');
                    });
                }
            );
        });
    },
};


module.exports = profileController;
