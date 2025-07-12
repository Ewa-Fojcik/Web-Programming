// interface.pretty.js - Corrected Version
// COMP519 Assignment 4 - API Interface JavaScript

/**
 * Sends an HTTP request to the REST API based on form input
 */
function sendRequest() {
    var method = document.getElementById('method').value;
    var resource = document.getElementById('resource').value.trim();
    var body = document.getElementById('body').value.trim();

    // Correct base URL to your ~sgefojci public_html root
    var baseUrl = 'https://student.csc.liv.ac.uk/~sgefojci';
    var fullUrl = baseUrl;

    if (resource.startsWith('/')) {
        resource = resource.slice(1);
    }

    if (resource.length > 0) {
        fullUrl += '/' + resource;
    }

    var xhr = new XMLHttpRequest();

    xhr.open(method, fullUrl, true);
    xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            document.getElementById('status').textContent = xhr.status;
            document.getElementById('response').textContent = xhr.responseText;
        }
    };

    if (method === 'POST' || method === 'PATCH') {
        if (body.length > 0) {
            xhr.send(body);
        } else {
            xhr.send('{}');
        }
    } else {
        xhr.send();
    }
}


/**
 * Clears the response areas
 */
function clearResponse() {
    document.getElementById('status').textContent = '';
    document.getElementById('response').textContent = '';
}
