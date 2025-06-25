// Global variables to demonstrate state management
let clickCount = 0;
const colors = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c'];
let colorIndex = 0;

// Function to update the current time display
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleString();
    document.getElementById('current-time').textContent = timeString;
}

// Function to increment the click counter
function incrementCounter() {
    clickCount++;
    document.getElementById('click-counter').textContent = clickCount;
    
    // Add a small animation effect
    const counter = document.getElementById('click-counter');
    counter.style.transform = 'scale(1.2)';
    setTimeout(() => {
        counter.style.transform = 'scale(1)';
    }, 200);
}

// Function to change the color of the demo box
function changeColor() {
    const colorDemo = document.getElementById('color-demo');
    colorIndex = (colorIndex + 1) % colors.length;
    colorDemo.style.backgroundColor = colors[colorIndex];
    
    // Add transition effect
    colorDemo.style.transition = 'background-color 0.5s ease';
}

// Calculator function
function calculate() {
    const num1 = parseFloat(document.getElementById('num1').value);
    const num2 = parseFloat(document.getElementById('num2').value);
    const operation = document.getElementById('operation').value;
    const resultDiv = document.getElementById('result');
    
    // Validate input
    if (isNaN(num1) || isNaN(num2)) {
        resultDiv.innerHTML = '<span style="color: #e74c3c;">Please enter valid numbers</span>';
        return;
    }
    
    let result;
    switch (operation) {
        case '+':
            result = num1 + num2;
            break;
        case '-':
            result = num1 - num2;
            break;
        case '*':
            result = num1 * num2;
            break;
        case '/':
            if (num2 === 0) {
                resultDiv.innerHTML = '<span style="color: #e74c3c;">Error: Division by zero</span>';
                return;
            }
            result = num1 / num2;
            break;
        default:
            resultDiv.innerHTML = '<span style="color: #e74c3c;">Invalid operation</span>';
            return;
    }
    
    // Display result with formatting
    resultDiv.innerHTML = `<span style="color: #27ae60;">Result: ${result}</span>`;
}

// Form handling function
function handleFormSubmission(event) {
    event.preventDefault(); // Prevent default form submission
    
    // Get form data
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();
    const resultDiv = document.getElementById('form-result');
    
    // Basic validation
    if (!name || !email || !message) {
        resultDiv.className = 'form-result error';
        resultDiv.textContent = 'Please fill in all fields.';
        return;
    }
    
    // Email validation using regex
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        resultDiv.className = 'form-result error';
        resultDiv.textContent = 'Please enter a valid email address.';
        return;
    }
    
    // Simulate form processing
    resultDiv.className = 'form-result success';
    resultDiv.innerHTML = `
        <strong>Form submitted successfully!</strong><br>
        Name: ${name}<br>
        Email: ${email}<br>
        Message: ${message.substring(0, 50)}${message.length > 50 ? '...' : ''}
    `;
    
    // Clear form
    document.getElementById('contact-form').reset();
}

// Smooth scrolling for navigation links
function smoothScroll(target) {
    const element = document.querySelector(target);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Function to update last updated time
function updateLastUpdated() {
    const now = new Date();
    const dateString = now.toLocaleDateString();
    document.getElementById('last-updated').textContent = dateString;
}

// Animation for progress bars
function animateProgressBars() {
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach((bar, index) => {
        // Reset width
        bar.style.width = '0%';
        
        // Animate to target width with delay
        setTimeout(() => {
            bar.style.transition = 'width 2s ease';
            bar.style.width = bar.getAttribute('style').match(/width:\s*(\d+%)/)[1];
        }, index * 200);
    });
}

// Intersection Observer for scroll animations
function setupScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    // Observe all sections
    document.querySelectorAll('.section').forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(30px)';
        section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(section);
    });
}

// Keyboard shortcuts
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', (event) => {
        // Alt + T to update time
        if (event.altKey && event.key === 't') {
            event.preventDefault();
            updateTime();
        }
        
        // Alt + C to increment counter
        if (event.altKey && event.key === 'c') {
            event.preventDefault();
            incrementCounter();
        }
        
        // Alt + R to change color
        if (event.altKey && event.key === 'r') {
            event.preventDefault();
            changeColor();
        }
    });
}

// Event listeners setup
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the page
    updateTime();
    updateLastUpdated();
    
    // Set up form submission
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', handleFormSubmission);
    }
    
    // Set up navigation smooth scrolling
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const target = this.getAttribute('href');
            smoothScroll(target);
        });
    });
    
    // Set up scroll animations
    setupScrollAnimations();
    
    // Set up keyboard shortcuts
    setupKeyboardShortcuts();
    
    // Animate progress bars when page loads
    setTimeout(animateProgressBars, 1000);
    
    // Update time every minute
    setInterval(updateTime, 60000);
    
    // Add CSS transition styles for interactive elements
    const style = document.createElement('style');
    style.textContent = `
        #click-counter {
            transition: transform 0.2s ease;
        }
        
        .info-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .btn {
            transition: all 0.3s ease;
        }
    `;
    document.head.appendChild(style);
    
    // Console message for developers
    console.log('🚀 Web Development Learning Page loaded successfully!');
    console.log('💡 Try these keyboard shortcuts:');
    console.log('   Alt + T: Update time');
    console.log('   Alt + C: Increment counter');
    console.log('   Alt + R: Change color');
});

// Utility functions for learning purposes

// Function to demonstrate array manipulation
function demonstrateArrays() {
    const fruits = ['apple', 'banana', 'orange'];
    console.log('Original array:', fruits);
    
    // Add element
    fruits.push('grape');
    console.log('After push:', fruits);
    
    // Remove element
    const removed = fruits.pop();
    console.log('Removed element:', removed);
    console.log('Final array:', fruits);
}

// Function to demonstrate object manipulation
function demonstrateObjects() {
    const person = {
        name: 'John Doe',
        age: 30,
        city: 'New York'
    };
    
    console.log('Person object:', person);
    console.log('Person name:', person.name);
    
    // Add property
    person.occupation = 'Developer';
    console.log('After adding occupation:', person);
}

// Function to demonstrate loops
function demonstrateLoops() {
    console.log('For loop example:');
    for (let i = 1; i <= 5; i++) {
        console.log(`Number: ${i}`);
    }
    
    console.log('Array forEach example:');
    const colors = ['red', 'green', 'blue'];
    colors.forEach((color, index) => {
        console.log(`Color ${index + 1}: ${color}`);
    });
}

// Function to demonstrate conditional statements
function demonstrateConditionals(number) {
    if (number > 0) {
        console.log(`${number} is positive`);
    } else if (number < 0) {
        console.log(`${number} is negative`);
    } else {
        console.log('Number is zero');
    }
}

// Make demonstration functions available globally for console testing
window.demonstrateArrays = demonstrateArrays;
window.demonstrateObjects = demonstrateObjects;
window.demonstrateLoops = demonstrateLoops;
window.demonstrateConditionals = demonstrateConditionals;

// Error handling example
function demonstrateErrorHandling(value) {
    try {
        if (typeof value !== 'number') {
            throw new Error('Value must be a number');
        }
        console.log('Square root:', Math.sqrt(value));
    } catch (error) {
        console.error('Error occurred:', error.message);
    } finally {
        console.log('Error handling demonstration completed');
    }
}

window.demonstrateErrorHandling = demonstrateErrorHandling;
