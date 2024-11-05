import React, { useEffect, useState } from 'react';
import { Navigate } from 'react-router-dom';
import Cookies from 'js-cookie';

const PrivateRoute = ({ children }) => {
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        const token = Cookies.get('jwt_token');
        console.log(token);
        if (token) {
            setIsAuthenticated(true);
        }
        setIsLoading(false); // Complete the loading
    }, []);

    if (isLoading) {
        return null; // Optionally, render a loader here if desired
    }

    return isAuthenticated ? children : <Navigate to="/login" replace />;
};

export default PrivateRoute;
