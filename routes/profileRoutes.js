// routes/profileRoutes.js
const express = require('express');
const profileController = require('../controllers/profileController');
const router = express.Router();

router.get('/update-profile', profileController.getUpdateProfile);
router.post('/update-profile', profileController.postUpdateProfile);

// Add the dashboard route
// routes/profileRoutes.js
// routes/profileRoutes.js
// routes/profileRoutes.js
router.get('/dashboard', (req, res) => {
    if (req.session.user) {
        const { username, age, address } = req.session.user;
        res.render('dashboard.ejs', { user: { username, age, address } });
    } else {
        res.redirect('/login');
    }
});




module.exports = router;
