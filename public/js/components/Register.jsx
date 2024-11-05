// src/components/Register.jsx

import React, { useState } from 'react';
import axios from '../utils/axiosConfig';
import {
    TextField,
    Button,
    Typography,
    Box,
    Alert,
} from '@mui/material';
import { useNavigate } from 'react-router-dom';

const Register = () => {
    const [formData, setFormData] = useState({
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirm: '',
    });
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setError(''); // Reset error message

        axios
            .post('/register', formData)
            .then((response) => {
                if (response.status === 201 || response.status === 200) {
                    navigate('/chat'); // Redirect to chat application
                } else {
                    setError('Registration failed. Please try again.');
                }
            })
            .catch((error) => {
                console.error('Registration error:', error);
                const errorMessage =
                    error.response?.data?.message ||
                    (error.response?.data?.errors &&
                        Object.values(error.response.data.errors).join(' ')) ||
                    'An error occurred during registration.';
                setError(errorMessage);
            });
    };

    return (
        <Box maxWidth={400} mx="auto" mt={5}>
            <Typography variant="h4" gutterBottom>
                Register
            </Typography>
            {error && (
                <Alert severity="error" sx={{ mb: 2 }}>
                    {error}
                </Alert>
            )}
            <form onSubmit={handleSubmit}>
                <TextField
                    label="First Name"
                    variant="outlined"
                    fullWidth
                    name="first_name"
                    value={formData.first_name}
                    onChange={handleChange}
                    margin="normal"
                    required
                />
                <TextField
                    label="Last Name"
                    variant="outlined"
                    fullWidth
                    name="last_name"
                    value={formData.last_name}
                    onChange={handleChange}
                    margin="normal"
                    required
                />
                <TextField
                    label="Email"
                    variant="outlined"
                    fullWidth
                    name="email"
                    type="email"
                    value={formData.email}
                    onChange={handleChange}
                    margin="normal"
                    required
                />
                <TextField
                    label="Password"
                    variant="outlined"
                    fullWidth
                    name="password"
                    type="password"
                    value={formData.password}
                    onChange={handleChange}
                    margin="normal"
                    required
                />
                <TextField
                    label="Confirm Password"
                    variant="outlined"
                    fullWidth
                    name="password_confirm"
                    type="password"
                    value={formData.password_confirm}
                    onChange={handleChange}
                    margin="normal"
                    required
                />
                <Button
                    type="submit"
                    variant="contained"
                    color="primary"
                    fullWidth
                    sx={{ mt: 2 }}
                >
                    Register
                </Button>
                <Button
                    onClick={() => navigate('/login')}
                    color="secondary"
                    fullWidth
                    sx={{ mt: 1 }}
                >
                    Already have an account? Login
                </Button>
            </form>
        </Box>
    );
};

export default Register;
