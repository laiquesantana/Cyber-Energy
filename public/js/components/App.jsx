// src/components/App.jsx

import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Login from './Login';
import Register from './Register';
import ChatApp from './ChatApp'; // The chat component you already have
import ChatHistory from './ChatHistory'; // If you have a history component
import PrivateRoute from './PrivateRoute'; // We'll create this next
import Layout from './Layout'; // If you have a layout component

const App = () => {
    return (
        <Router>
            <Routes>
                <Route path="/" element={<Login />} /> {/* Default to Login */}
                <Route path="/login" element={<Login />} />
                <Route path="/register" element={<Register />} />
                {/* Protected Routes */}
                <Route
                    path="/chat"
                    element={
                        <PrivateRoute>
                            <Layout>
                                <ChatApp />
                            </Layout>
                        </PrivateRoute>
                    }
                />
                <Route
                    path="/history"
                    element={
                        <PrivateRoute>
                            <Layout>
                                <ChatHistory />
                            </Layout>
                        </PrivateRoute>
                    }
                />
            </Routes>
        </Router>
    );
};

export default App;
