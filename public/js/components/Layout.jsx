
import React from 'react';
import { useNavigate } from 'react-router-dom';
import { AppBar, Toolbar, Typography, Button } from '@mui/material';
import axios from '../utils/axiosConfig';

const Layout = ({ children }) => {
    const navigate = useNavigate();

    const handleLogout = () => {
        axios
            .post('/logout')
            .then(() => {
                localStorage.removeItem('jwt_token');
                navigate('/login');
            })
            .catch((error) => {
                console.error('Logout error:', error);
            });
    };

    return (
        <>
            <AppBar position="static">
                <Toolbar>
                    <Typography variant="h6" sx={{ flexGrow: 1 }}>
                        Chat Application
                    </Typography>
                    <Button color="inherit" onClick={() => navigate('/chat')}>
                        Chat
                    </Button>
                    <Button color="inherit" onClick={() => navigate('/history')}>
                        History
                    </Button>
                    <Button color="inherit" onClick={handleLogout}>
                        Logout
                    </Button>
                </Toolbar>
            </AppBar>
            <div>{children}</div>
        </>
    );
};

export default Layout;
