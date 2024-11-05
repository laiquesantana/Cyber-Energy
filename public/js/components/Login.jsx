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

const Login = () => {
    const [formData, setFormData] = useState({
        email: '',
        password: '',
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
            .post('/login', formData)
            .then((response) => {
                if (response.status === 200) {
                    navigate('/chat');
                } else {
                    setError('Login failed. Please try again.');
                }
            })
            .catch((error) => {
                console.error('Login error:', error);
                const errorMessage =
                    error.response?.data?.message ||
                    'An error occurred during login.';
                setError(errorMessage);
            });
    };

    return (
        <Box maxWidth={400} mx="auto" mt={5}>
            <Typography variant="h4" gutterBottom>
                Login
            </Typography>
            {error && (
                <Alert severity="error" sx={{ mb: 2 }}>
                    {error}
                </Alert>
            )}
            <form onSubmit={handleSubmit}>
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
                <Button
                    type="submit"
                    variant="contained"
                    color="primary"
                    fullWidth
                    sx={{ mt: 2 }}
                >
                    Login
                </Button>
                <Button
                    onClick={() => navigate('/register')}
                    color="secondary"
                    fullWidth
                    sx={{ mt: 1 }}
                >
                    Don't have an account? Register
                </Button>
            </form>
        </Box>
    );
};

export default Login;
