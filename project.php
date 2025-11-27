<section id="project-section">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Project Management</h1>

    <!-- Controls -->
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
        <input type="text" id="projectSearch" placeholder="Search projects..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-purple-500">
        <button id="addProjectBtn" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="ri-add-line"></i> Add Project
        </button>
    </div>

    <!-- Project Table -->
    <div class="overflow-x-auto bg-white rounded-2xl shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Objectives</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Timeline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Budget</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="projectTable" class="bg-white divide-y divide-gray-200"></tbody>
        </table>
    </div>
</section>

<!-- Modal for Add/Edit Project -->
<div id="projectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-semibold mb-4" id="projectModalTitle">Add Project</h2>
        <form id="projectForm" class="space-y-3">
            <input type="text" id="projectName" placeholder="Project Name" class="w-full px-4 py-2 border rounded-lg">
            
            <select id="projectProgram" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Select Program</option>
            </select>

            <textarea id="projectObjectives" placeholder="Objectives" class="w-full px-4 py-2 border rounded-lg"></textarea>
            <input type="text" id="projectBeneficiaries" placeholder="Target Beneficiaries" class="w-full px-4 py-2 border rounded-lg">
            <input type="text" id="projectTimeline" placeholder="Timeline (e.g., Jan 2025 - Dec 2025)" class="w-full px-4 py-2 border rounded-lg">
            <input type="number" id="projectBudget" placeholder="Budget" class="w-full px-4 py-2 border rounded-lg">
            <input type="text" id="projectCoordinators" placeholder="Coordinators / Staff" class="w-full px-4 py-2 border rounded-lg">
            <input type="text" id="projectResources" placeholder="Resources & Locations" class="w-full px-4 py-2 border rounded-lg">
            
            <select id="projectStatus" class="w-full px-4 py-2 border rounded-lg">
                <option value="Planned">Planned</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Completed">Completed</option>
            </select>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" id="cancelProjectBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-purple-500 text-white hover:bg-purple-600">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
// Project Management Logic
let projects = [];
let projectEditIndex = null;

const projectTable = document.getElementById('projectTable');
const projectModal = document.getElementById('projectModal');
const projectForm = document.getElementById('projectForm');
const projectModalTitle = document.getElementById('projectModalTitle');
const projectSearch = document.getElementById('projectSearch');
const projectProgramSelect = document.getElementById('projectProgram');

// Populate Program options dynamically
function populateProgramOptions() {
    projectProgramSelect.innerHTML = '<option value="">Select Program</option>';
    programs.forEach((p, i) => {
        const opt = document.createElement('option');
        opt.value = p.name;
        opt.textContent = p.name;
        projectProgramSelect.appendChild(opt);
    });
}

function renderProjects(filter='') {
    projectTable.innerHTML = '';
    projects
        .filter(p => p.name.toLowerCase().includes(filter.toLowerCase()))
        .forEach((p, i) => {
            const row = document.createElement('tr');
            row.classList.add('hover:bg-gray-50');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">${p.name}</td>
                <td class="px-6 py-4 whitespace-nowrap">${p.program}</td>
                <td class="px-6 py-4 whitespace-nowrap">${p.objectives}</td>
                <td class="px-6 py-4 whitespace-nowrap">${p.timeline}</td>
                <td class="px-6 py-4 whitespace-nowrap">${p.budget}</td>
                <td class="px-6 py-4 whitespace-nowrap">${p.status}</td>
                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                    <button class="text-purple-500 hover:text-purple-700" onclick="editProject(${i})"><i class="ri-edit-line"></i></button>
                    <button class="text-red-500 hover:text-red-700" onclick="deleteProject(${i})"><i class="ri-delete-bin-line"></i></button>
                </td>
            `;
            projectTable.appendChild(row);
        });
}

function openProjectModal(edit=false) {
    populateProgramOptions();
    projectModal.classList.remove('hidden');
    projectModalTitle.textContent = edit ? 'Edit Project' : 'Add Project';
}

function closeProjectModal() {
    projectModal.classList.add('hidden');
    projectForm.reset();
    projectEditIndex = null;
}

function editProject(index) {
    projectEditIndex = index;
    const p = projects[index];
    document.getElementById('projectName').value = p.name;
    document.getElementById('projectProgram').value = p.program;
    document.getElementById('projectObjectives').value = p.objectives;
    document.getElementById('projectBeneficiaries').value = p.beneficiaries;
    document.getElementById('projectTimeline').value = p.timeline;
    document.getElementById('projectBudget').value = p.budget;
    document.getElementById('projectCoordinators').value = p.coordinators;
    document.getElementById('projectResources').value = p.resources;
    document.getElementById('projectStatus').value = p.status;
    openProjectModal(true);
}

function deleteProject(index) {
    if(confirm('Are you sure you want to delete this project?')) {
        projects.splice(index, 1);
        renderProjects(projectSearch.value);
    }
}

// Event Listeners
document.getElementById('addProjectBtn').addEventListener('click', () => openProjectModal());
document.getElementById('cancelProjectBtn').addEventListener('click', () => closeProjectModal());

projectForm.addEventListener('submit', e => {
    e.preventDefault();
    const name = document.getElementById('projectName').value;
    const program = document.getElementById('projectProgram').value;
    const objectives = document.getElementById('projectObjectives').value;
    const beneficiaries = document.getElementById('projectBeneficiaries').value;
    const timeline = document.getElementById('projectTimeline').value;
    const budget = document.getElementById('projectBudget').value;
    const coordinators = document.getElementById('projectCoordinators').value;
    const resources = document.getElementById('projectResources').value;
    const status = document.getElementById('projectStatus').value;

    const projectData = {name, program, objectives, beneficiaries, timeline, budget, coordinators, resources, status};

    if(projectEditIndex !== null) {
        projects[projectEditIndex] = projectData;
    } else {
        projects.push(projectData);
    }

    renderProjects(projectSearch.value);
    closeProjectModal();
});

projectSearch.addEventListener('input', e => renderProjects(e.target.value));

// Optional: Add dummy data
projects.push({name:'Community Health Project', program:'Health Program', objectives:'Improve wellness', beneficiaries:'Community', timeline:'Jan-Dec 2025', budget:50000, coordinators:'Juan Dela Cruz', resources:'Clinic, Medicines', status:'Planned'});
renderProjects();
</script>