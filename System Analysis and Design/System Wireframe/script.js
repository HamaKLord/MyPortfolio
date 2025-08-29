// Global variables
let currentUser = null;
let quotations = [];
let customers = [];

// Navigation function
function navigateTo(page) {
    window.location.href = page;
}

// Form validation functions
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validatePhone(phone) {
    const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

function validateRequired(value) {
    return value && value.trim().length > 0;
}

// Show/hide messages
function showMessage(message, type = 'success') {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message message-${type}`;
    messageDiv.textContent = message;
    
    const container = document.querySelector('.form-container') || document.querySelector('.dashboard');
    container.insertBefore(messageDiv, container.firstChild);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 5000);
}

// Customer Request Form Handler
function handleCustomerRequest(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    // Validation
    if (!validateRequired(data.fullName)) {
        showMessage('Full name is required', 'error');
        return;
    }
    
    if (!validateEmail(data.email)) {
        showMessage('Please enter a valid email address', 'error');
        return;
    }
    
    if (!validatePhone(data.phone)) {
        showMessage('Please enter a valid phone number', 'error');
        return;
    }
    
    if (!validateRequired(data.serviceType)) {
        showMessage('Please select a service type', 'error');
        return;
    }
    
    // Simulate form submission
    const submitBtn = event.target.querySelector('.form-btn');
    const originalText = submitBtn.textContent;
    submitBtn.innerHTML = '<span class="loading"></span> Submitting...';
    submitBtn.disabled = true;
    
    setTimeout(() => {
        // Store in localStorage for demo
        const requests = JSON.parse(localStorage.getItem('customerRequests') || '[]');
        const newRequest = {
            id: Date.now(),
            ...data,
            status: 'pending',
            date: new Date().toISOString()
        };
        requests.push(newRequest);
        localStorage.setItem('customerRequests', JSON.stringify(requests));
        
        showMessage('Quotation request submitted successfully! Reference: #' + newRequest.id, 'success');
        
        // Show confirmation
        showConfirmation(newRequest);
        
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 2000);
}

// Show confirmation screen
function showConfirmation(request) {
    const container = document.querySelector('.form-container');
    container.innerHTML = `
        <div class="confirmation-screen fade-in">
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="form-title">Request Submitted Successfully!</h2>
            <div class="confirmation-details">
                <p><strong>Reference Number:</strong> #${request.id}</p>
                <p><strong>Name:</strong> ${request.fullName}</p>
                <p><strong>Service:</strong> ${request.serviceType}</p>
                <p><strong>Expected Response:</strong> Within 3 working days</p>
            </div>
            <p class="confirmation-note">
                We have sent a confirmation email to ${request.email}. 
                Our team will review your request and get back to you soon.
            </p>
            <button class="form-btn" onclick="navigateTo('index.html')">Back to Home</button>
        </div>
    `;
}

// Employee Dashboard Functions
function loadEmployeeDashboard() {
    const requests = JSON.parse(localStorage.getItem('customerRequests') || '[]');
    const quotations = JSON.parse(localStorage.getItem('quotations') || '[]');
    
    updateStats(requests, quotations);
    displayQuotations(quotations);
}

function updateStats(requests, quotations) {
    const statsContainer = document.querySelector('.stats-grid');
    if (!statsContainer) return;
    
    const pendingRequests = requests.filter(r => r.status === 'pending').length;
    const totalQuotations = quotations.length;
    const approvedQuotations = quotations.filter(q => q.status === 'approved').length;
    const pendingQuotations = quotations.filter(q => q.status === 'pending').length;
    
    statsContainer.innerHTML = `
        <div class="stat-card">
            <div class="stat-number">${pendingRequests}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${totalQuotations}</div>
            <div class="stat-label">Total Quotations</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${approvedQuotations}</div>
            <div class="stat-label">Approved</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${pendingQuotations}</div>
            <div class="stat-label">Pending Approval</div>
        </div>
    `;
}

function displayQuotations(quotations) {
    const tableBody = document.querySelector('.quotations-table tbody');
    if (!tableBody) return;
    
    if (quotations.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">No quotations found</td></tr>';
        return;
    }
    
    tableBody.innerHTML = quotations.map(quote => `
        <tr>
            <td>#${quote.id}</td>
            <td>${quote.customerName}</td>
            <td>${quote.serviceType}</td>
            <td>IQD ${quote.totalAmount}</td>
            <td><span class="status-badge status-${quote.status}">${quote.status}</span></td>
            <td>
                <button class="action-btn btn-view" onclick="viewQuotation(${quote.id})">View</button>
                ${quote.status === 'pending' ? `
                    <button class="action-btn btn-edit" onclick="editQuotation(${quote.id})">Edit</button>
                ` : ''}
            </td>
        </tr>
    `).join('');
}

// Manager Dashboard Functions
function loadManagerDashboard() {
    let quotations = JSON.parse(localStorage.getItem('quotations') || '[]');
    // If no quotations, add a sample pending one for demo
    if (quotations.length === 0) {
        const sampleQuotation = {
            id: 2001,
            customerName: "Sara Ahmed",
            customerEmail: "sara.ahmed@example.com",
            serviceType: "Education",
            description: "Quotation for school renovation.",
            unitPrice: "3000000",
            quantity: "2",
            totalAmount: "6000000",
            notes: "Please process quickly.",
            status: "pending",
            createdDate: new Date().toISOString(),
            employeeId: "EMP002"
        };
        quotations.push(sampleQuotation);
        localStorage.setItem('quotations', JSON.stringify(quotations));
    }
    updateManagerStats(quotations);
    displayPendingQuotations(quotations.filter(q => q.status === 'pending'));
}

function updateManagerStats(quotations) {
    const statsContainer = document.querySelector('.stats-grid');
    if (!statsContainer) return;
    
    const total = quotations.length;
    const pending = quotations.filter(q => q.status === 'pending').length;
    const approved = quotations.filter(q => q.status === 'approved').length;
    const rejected = quotations.filter(q => q.status === 'rejected').length;
    
    statsContainer.innerHTML = `
        <div class="stat-card">
            <div class="stat-number">${total}</div>
            <div class="stat-label">Total Quotations</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${pending}</div>
            <div class="stat-label">Pending Review</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${approved}</div>
            <div class="stat-label">Approved</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${rejected}</div>
            <div class="stat-label">Rejected</div>
        </div>
    `;
}

function displayPendingQuotations(quotations) {
    const tableBody = document.querySelector('.pending-quotations-table tbody');
    if (!tableBody) return;
    
    if (quotations.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">No pending quotations</td></tr>';
        return;
    }
    
    tableBody.innerHTML = quotations.map(quote => `
        <tr>
            <td>#${quote.id}</td>
            <td>${quote.customerName}</td>
            <td>${quote.customerEmail}</td>
            <td>${quote.serviceType}</td>
            <td>${quote.description}</td>
            <td>IQD ${quote.totalAmount}</td>
            <td>
                <button class="action-btn btn-approve" onclick="approveQuotation(${quote.id})">Approve</button>
                <button class="action-btn btn-reject" onclick="rejectQuotation(${quote.id})">Reject</button>
                <button class="action-btn btn-view" onclick="viewQuotation(${quote.id})">View</button>
            </td>
        </tr>
    `).join('');
}

// Quotation Actions
function approveQuotation(id) {
    const quotations = JSON.parse(localStorage.getItem('quotations') || '[]');
    const quotation = quotations.find(q => q.id === id);
    
    if (quotation) {
        quotation.status = 'approved';
        quotation.approvedDate = new Date().toISOString();
        localStorage.setItem('quotations', JSON.stringify(quotations));
        
        showMessage('Quotation approved successfully!', 'success');
        loadManagerDashboard();
    }
}

function rejectQuotation(id) {
    const quotations = JSON.parse(localStorage.getItem('quotations') || '[]');
    const quotation = quotations.find(q => q.id === id);
    
    if (quotation) {
        quotation.status = 'rejected';
        quotation.rejectedDate = new Date().toISOString();
        localStorage.setItem('quotations', JSON.stringify(quotations));
        
        showMessage('Quotation rejected', 'warning');
        loadManagerDashboard();
    }
}

function viewQuotation(id) {
    const quotations = JSON.parse(localStorage.getItem('quotations') || '[]');
    const quotation = quotations.find(q => q.id === id);
    
    if (quotation) {
        showQuotationDetails(quotation);
    }
}

function showQuotationDetails(quotation) {
    const modal = document.createElement('div');
    modal.className = 'modal fade-in';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Quotation Details #${quotation.id}</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="quotation-details">
                    <div class="detail-row">
                        <strong>Customer:</strong> ${quotation.customerName}
                    </div>
                    <div class="detail-row">
                        <strong>Email:</strong> ${quotation.customerEmail}
                    </div>
                    <div class="detail-row">
                        <strong>Service:</strong> ${quotation.serviceType}
                    </div>
                    <div class="detail-row">
                        <strong>Description:</strong> ${quotation.description}
                    </div>
                    <div class="detail-row">
                        <strong>Total Amount:</strong> IQD ${quotation.totalAmount}
                    </div>
                    <div class="detail-row">
                        <strong>Status:</strong> <span class="status-badge status-${quotation.status}">${quotation.status}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Created:</strong> ${new Date(quotation.createdDate).toLocaleDateString()}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="form-btn" onclick="closeModal()">Close</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

function closeModal() {
    const modal = document.querySelector('.modal');
    if (modal) {
        modal.remove();
    }
}

// Admin Dashboard Functions
function loadAdminDashboard() {
    let quotations = JSON.parse(localStorage.getItem('quotations') || '[]');
    // If no quotations, add a sample pending one for demo
    if (quotations.length === 0) {
        const sampleQuotation = {
            id: 2001,
            customerName: "Sara Ahmed",
            customerEmail: "sara.ahmed@example.com",
            serviceType: "Education",
            description: "Quotation for school renovation.",
            unitPrice: "3000000",
            quantity: "2",
            totalAmount: "6000000",
            notes: "Please process quickly.",
            status: "pending",
            createdDate: new Date().toISOString(),
            employeeId: "EMP002"
        };
        quotations.push(sampleQuotation);
        localStorage.setItem('quotations', JSON.stringify(quotations));
    }
    updateAdminStats(JSON.parse(localStorage.getItem('customerRequests') || '[]'), quotations);
    displayAllQuotations(quotations);
}

function updateAdminStats(requests, quotations) {
    const statsContainer = document.querySelector('.stats-grid');
    if (!statsContainer) return;
    
    const totalRequests = requests.length;
    const totalQuotations = quotations.length;
    const approvedQuotations = quotations.filter(q => q.status === 'approved').length;
    const totalRevenue = quotations
        .filter(q => q.status === 'approved')
        .reduce((sum, q) => sum + parseFloat(q.totalAmount), 0);
    
    statsContainer.innerHTML = `
        <div class="stat-card">
            <div class="stat-number">${totalRequests}</div>
            <div class="stat-label">Total Requests</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${totalQuotations}</div>
            <div class="stat-label">Total Quotations</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${approvedQuotations}</div>
            <div class="stat-label">Approved</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">IQD ${totalRevenue.toFixed(2)}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    `;
}

function displayAllQuotations(quotations) {
    const tableBody = document.querySelector('.all-quotations-table tbody');
    if (!tableBody) return;
    
    if (quotations.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">No quotations found</td></tr>';
        return;
    }
    
    tableBody.innerHTML = quotations.map(quote => `
        <tr>
            <td>#${quote.id}</td>
            <td>${quote.customerName}</td>
            <td>${quote.customerEmail}</td>
            <td>${quote.serviceType}</td>
            <td>IQD ${quote.totalAmount}</td>
            <td><span class="status-badge status-${quote.status}">${quote.status}</span></td>
            <td>${new Date(quote.createdDate).toLocaleDateString()}</td>
        </tr>
    `).join('');
}

// Report Generation
function generateReport() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const department = document.getElementById('department').value;
    const status = document.getElementById('status').value;
    
    if (!dateFrom || !dateTo) {
        showMessage('Please select date range', 'error');
        return;
    }
    
    const quotations = JSON.parse(localStorage.getItem('quotations') || '[]');
    const filteredQuotations = quotations.filter(quote => {
        const quoteDate = new Date(quote.createdDate);
        const fromDate = new Date(dateFrom);
        const toDate = new Date(dateTo);
        
        return quoteDate >= fromDate && quoteDate <= toDate &&
               (department === 'all' || quote.serviceType === department) &&
               (status === 'all' || quote.status === status);
    });
    
    displayReportResults(filteredQuotations);
}

function displayReportResults(quotations) {
    const resultsContainer = document.getElementById('reportResults');
    if (!resultsContainer) return;

    // If no quotations, show a sample report example
    if (quotations.length === 0) {
        quotations = [
            {
                id: 2001,
                customerName: "Sara Ahmed",
                serviceType: "Education",
                totalAmount: "6000000",
                status: "pending",
                createdDate: new Date().toISOString()
            },
            {
                id: 2002,
                customerName: "Ali Kareem",
                serviceType: "Housing",
                totalAmount: "5000000",
                status: "approved",
                createdDate: new Date().toISOString()
            }
        ];
    }

    const totalAmount = quotations.reduce((sum, q) => sum + parseFloat(q.totalAmount), 0);
    const approvedCount = quotations.filter(q => q.status === 'approved').length;
    const rejectedCount = quotations.filter(q => q.status === 'rejected').length;
    const pendingCount = quotations.filter(q => q.status === 'pending').length;

    resultsContainer.innerHTML = `
        <div class="report-summary">
            <h3>Report Summary</h3>
            <div class="summary-stats">
                <div class="summary-stat">
                    <strong>Total Quotations:</strong> ${quotations.length}
                </div>
                <div class="summary-stat">
                    <strong>Total Amount:</strong> IQD ${totalAmount.toFixed(2)}
                </div>
                <div class="summary-stat">
                    <strong>Approved:</strong> ${approvedCount}
                </div>
                <div class="summary-stat">
                    <strong>Rejected:</strong> ${rejectedCount}
                </div>
                <div class="summary-stat">
                    <strong>Pending:</strong> ${pendingCount}
                </div>
            </div>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    ${quotations.map(quote => `
                        <tr>
                            <td>#${quote.id}</td>
                            <td>${quote.customerName}</td>
                            <td>${quote.serviceType}</td>
                            <td>IQD ${quote.totalAmount}</td>
                            <td><span class="status-badge status-${quote.status}">${quote.status}</span></td>
                            <td>${new Date(quote.createdDate).toLocaleDateString()}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
        <button class="form-btn" onclick="downloadReport()">Download Report</button>
    `;
}

function downloadReport() {
    // Simulate report download
    showMessage('Report downloaded successfully!', 'success');
}

// Initialize page based on current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop();
    
    switch(currentPage) {
        case 'customer-request.html':
            // Initialize customer request form
            const form = document.getElementById('customerRequestForm');
            if (form) {
                form.addEventListener('submit', handleCustomerRequest);
            }
            break;
            
        case 'employee-dashboard.html':
            loadEmployeeDashboard();
            break;
            
        case 'manager-dashboard.html':
            loadManagerDashboard();
            break;
            
        case 'admin-dashboard.html':
            loadAdminDashboard();
            break;
            
        default:
            // Main page - no specific initialization needed
            break;
    }
    
    // Add fade-in animation to all elements
    const elements = document.querySelectorAll('.role-card, .feature, .form-container, .dashboard');
    elements.forEach(element => {
        element.classList.add('fade-in');
    });
});

// Add modal styles dynamically
const modalStyles = `
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    
    .modal-content {
        background: white;
        border-radius: 15px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #7f8c8d;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid #e0e0e0;
        text-align: right;
    }
    
    .quotation-details .detail-row {
        margin-bottom: 1rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .confirmation-screen {
        text-align: center;
    }
    
    .confirmation-icon {
        font-size: 4rem;
        color: #28a745;
        margin-bottom: 1rem;
    }
    
    .confirmation-details {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin: 1.5rem 0;
        text-align: left;
    }
    
    .confirmation-details p {
        margin-bottom: 0.5rem;
    }
    
    .confirmation-note {
        color: #6c757d;
        font-style: italic;
        margin-bottom: 1.5rem;
    }
    
    .report-summary {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .summary-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .summary-stat {
        text-align: center;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
`;

// Add styles to document
const styleSheet = document.createElement('style');
styleSheet.textContent = modalStyles;
document.head.appendChild(styleSheet); 