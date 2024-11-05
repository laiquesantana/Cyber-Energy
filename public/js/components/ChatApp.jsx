import React, { useState } from 'react';
import axios from '../utils/axiosConfig';
import {
    TextField,
    Button,
    CircularProgress,
    Typography,
    Box,
    Paper,
    List,
    ListItem,
    ListItemText,
} from '@mui/material';

const ChatApp = () => {
    const [messages, setMessages] = useState([]);
    const [userInput, setUserInput] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!userInput.trim()) return;

        setIsLoading(true);

        axios
            .post('/chat', { user_input: userInput })
            .then((response) => {
                setMessages([...messages, response.data]);
                setUserInput('');
            })
            .catch((error) => {
                console.error('Error sending the message:', error);
            })
            .finally(() => {
                setIsLoading(false);
            });
    };

    return (
        <Box>
            <Typography variant="h4" gutterBottom>
                Chat
            </Typography>
            <Paper style={{ padding: '1rem', marginBottom: '1rem' }}>
                <List>
                    {messages.map((msg, index) => (
                        <div key={msg.id || index}>
                            <ListItem>
                                <ListItemText
                                    primary={`User: ${msg.user_input}`}
                                    secondary={`AI: ${msg.ai_response}`}
                                />
                            </ListItem>
                        </div>
                    ))}
                    {isLoading && (
                        <Box display="flex" justifyContent="center" mt={2}>
                            <CircularProgress />
                        </Box>
                    )}
                </List>
            </Paper>
            <form onSubmit={handleSubmit}>
                <TextField
                    label="Input your message"
                    variant="outlined"
                    fullWidth
                    value={userInput}
                    onChange={(e) => setUserInput(e.target.value)}
                    disabled={isLoading}
                />
                <Box mt={2} display="flex" justifyContent="flex-end">
                    <Button
                        type="submit"
                        variant="contained"
                        color="primary"
                        disabled={isLoading}
                    >
                        Send
                    </Button>
                </Box>
            </form>
        </Box>
    );
};

export default ChatApp;
