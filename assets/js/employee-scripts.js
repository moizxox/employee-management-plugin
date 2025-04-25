document.addEventListener('DOMContentLoaded', function() {
    const employees = employeeData || [];
    const tableBody = document.getElementById('employee-table-body');
    const sortableHeaders = document.querySelectorAll('.sortable');

    let currentSort = {
        field: 'name',
        direction: 'asc'
    };

    if (employees.length > 0) {
        sortEmployees('name', 'asc');
        updateSortIndicators('name', 'asc');
    }

    sortableHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const sortField = header.dataset.sort;

            if (currentSort.field === sortField) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.field = sortField;
                currentSort.direction = 'asc';
            }

            sortEmployees(sortField, currentSort.direction);
            updateSortIndicators(sortField, currentSort.direction);
        });
    });

    function sortEmployees(field, direction) {
        const sortedEmployees = [...employees].sort((a, b) => {
            let valueA = a[field];
            let valueB = b[field];

            if (field === 'salary') {
                valueA = parseFloat(valueA) || 0;
                valueB = parseFloat(valueB) || 0;
            }

            if (field === 'date_of_hire') {
                valueA = new Date(valueA).getTime() || 0;
                valueB = new Date(valueB).getTime() || 0;
            }

            if (valueA < valueB) {
                return direction === 'asc' ? -1 : 1;
            }
            if (valueA > valueB) {
                return direction === 'asc' ? 1 : -1;
            }
            return 0;
        });

        renderTable(sortedEmployees);
    }

    function renderTable(employees) {
        tableBody.innerHTML = '';

        if (employees.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="empty-message">No employees found.</td></tr>';
            return;
        }

        employees.forEach(employee => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${escapeHtml(employee.name)}</td>
                <td>${escapeHtml(employee.email)}</td>
                <td>${escapeHtml(employee.position)}</td>
                <td>${escapeHtml(employee.salary)}</td>
                <td>${escapeHtml(employee.date_of_hire)}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    function updateSortIndicators(field, direction) {
        sortableHeaders.forEach(header => {
            header.classList.remove('sorted-asc', 'sorted-desc');
        });

        const currentHeader = document.querySelector(`[data-sort="${field}"]`);
        if (currentHeader) {
            currentHeader.classList.add(direction === 'asc' ? 'sorted-asc' : 'sorted-desc');
        }
    }

    function escapeHtml(unsafe) {
        if (unsafe === null || unsafe === undefined) return '';
        return String(unsafe)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});