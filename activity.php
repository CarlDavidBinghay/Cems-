<section id="activity-section">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Activity Management</h1>

    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
        <input type="text" id="activitySearch" placeholder="Search activities..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-pink-500">
        <button id="addActivityBtn" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="ri-add-line"></i> Add Activity
        </button>
    </div>

    <div class="overflow-x-auto bg-white rounded-2xl shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Resources</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expected Output</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="activityTable" class="bg-white divide-y divide-gray-200"></tbody>
        </table>
    </div>
</section>

<!-- Activity Modal -->
<div id="activityModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-semibold mb-4" id="activityModalTitle">Add Activity</h2>
        <form id="activityForm" class="space-y-3">
            <input type="text" id="activityName" placeholder="Activity Name" class="w-full px-4 py-2 border rounded-lg">
            
            <select id="activityProject" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Select Project</option>
            </select>

            <input type="datetime-local" id="activityDateTime" class="w-full px-4 py-2 border rounded-lg">
            <input type="text" id="activityLocation" placeholder="Location" class="w-full px-4 py-2 border rounded-lg">
            <input type="text" id="activityResources" placeholder="Resources" class="w-full px-4 py-2 border rounded-lg">
            <textarea id="activityOutput" placeholder="Expected Output / Actual Accomplishments" class="w-full px-4 py-2 border rounded-lg"></textarea>
            <input type="text" id="activityAttendance" placeholder="Attendance Tracking" class="w-full px-4 py-2 border rounded-lg">

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" id="cancelActivityBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-pink-500 text-white hover:bg-pink-600">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
// Activity Management
let activities = [];
let activityEditIndex = null;

const activityTable = document.getElementById('activityTable');
const activityModal = document.getElementById('activityModal');
const activityForm = document.getElementById('activityForm');
const activityModalTitle = document.getElementById('activityModalTitle');
const activitySearch = document.getElementById('activitySearch');
const activityProjectSelect = document.getElementById('activityProject');

function populateActivityProjects() {
    activityProjectSelect.innerHTML = '<option value="">Select Project</option>';
    projects.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.name;
        opt.textContent = p.name;
        activityProjectSelect.appendChild(opt);
    });
}

function renderActivities(filter='') {
    activityTable.innerHTML = '';
    activities.filter(a => a.activityName.toLowerCase().includes(filter.toLowerCase()))
        .forEach((a, i) => {
            const row = document.createElement('tr');
            row.classList.add('hover:bg-gray-50');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">${a.activityName}</td>
                <td class="px-6 py-4 whitespace-nowrap">${a.project}</td>
                <td class="px-6 py-4 whitespace-nowrap">${a.dateTime}</td>
                <td class="px-6 py-4 whitespace-nowrap">${a.location}</td>
                <td class="px-6 py-4 whitespace-nowrap">${a.resources}</td>
                <td class="px-6 py-4 whitespace-nowrap">${a.output}</td>
                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                    <button class="text-pink-500 hover:text-pink-700" onclick="editActivity(${i})"><i class="ri-edit-line"></i></button>
                    <button class="text-red-500 hover:text-red-700" onclick="deleteActivity(${i})"><i class="ri-delete-bin-line"></i></button>
                </td>
            `;
            activityTable.appendChild(row);
        });
}

function openActivityModal(edit=false) {
    populateActivityProjects();
    activityModal.classList.remove('hidden');
    activityModalTitle.textContent = edit ? 'Edit Activity' : 'Add Activity';
}

function closeActivityModal() {
    activityModal.classList.add('hidden');
    activityForm.reset();
    activityEditIndex = null;
}

function editActivity(index) {
    activityEditIndex = index;
    const a = activities[index];
    document.getElementById('activityName').value = a.activityName;
    document.getElementById('activityProject').value = a.project;
    document.getElementById('activityDateTime').value = a.dateTime;
    document.getElementById('activityLocation').value = a.location;
    document.getElementById('activityResources').value = a.resources;
    document.getElementById('activityOutput').value = a.output;
    document.getElementById('activityAttendance').value = a.attendance;
    openActivityModal(true);
}

function deleteActivity(index) {
    if(confirm('Are you sure you want to delete this activity?')) {
        activities.splice(index, 1);
        renderActivities(activitySearch.value);
    }
}

document.getElementById('addActivityBtn').addEventListener('click', () => openActivityModal());
document.getElementById('cancelActivityBtn').addEventListener('click', () => closeActivityModal());

activityForm.addEventListener('submit', e => {
    e.preventDefault();
    const activityName = document.getElementById('activityName').value;
    const project = document.getElementById('activityProject').value;
    const dateTime = document.getElementById('activityDateTime').value;
    const location = document.getElementById('activityLocation').value;
    const resources = document.getElementById('activityResources').value;
    const output = document.getElementById('activityOutput').value;
    const attendance = document.getElementById('activityAttendance').value;

    const activityData = {activityName, project, dateTime, location, resources, output, attendance};

    if(activityEditIndex !== null) {
        activities[activityEditIndex] = activityData;
    } else {
        activities.push(activityData);
    }

    renderActivities(activitySearch.value);
    closeActivityModal();
});

activitySearch.addEventListener('input', e => renderActivities(e.target.value));
</script>