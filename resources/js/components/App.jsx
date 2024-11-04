import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Layout from './Layout';
import ChatApp from './ChatApp';
import ChatHistory from './ChatHistory';

const App = () => {
    return (
        <Router>
            <Layout>
                <Routes>
                    <Route path="/" element={<ChatApp />} />
                    <Route path="/history" element={<ChatHistory />} />
                </Routes>
            </Layout>
        </Router>
    );
};

export default App;
