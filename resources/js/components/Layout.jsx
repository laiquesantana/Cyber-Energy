import React from 'react';
import { AppBar, Toolbar, Typography, Container, Button } from '@mui/material';
import { Link } from 'react-router-dom';

const Layout = ({ children }) => {
    return (
        <>
            <AppBar position="static">
                <Toolbar>
                    <Typography variant="h6" style={{ flexGrow: 1 }}>
                        Cyber Energia Chat
                    </Typography>
                    <Button color="inherit" component={Link} to="/">
                        Chat
                    </Button>
                    <Button color="inherit" component={Link} to="/history">
                        Histórico
                    </Button>
                </Toolbar>
            </AppBar>
            <Container maxWidth="md" style={{ marginTop: '2rem' }}>
                {children}
            </Container>
        </>
    );
};

export default Layout;
