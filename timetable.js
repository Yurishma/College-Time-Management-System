const departmentEl = document.getElementById('department');
const levelEl = document.getElementById('level');
const generateBtn = document.getElementById('generateBtn');

const levels = {
    BCA: 8,
    BBM: 8,
    BBS: 4
};

departmentEl.addEventListener('change', function () {
    const dept = this.value;
    levelEl.innerHTML = '<option value="">-- Select --</option>';
    if (dept) {
        const count = levels[dept];
        const label = dept === 'BBS' ? 'Year' : 'Semester';
        for (let i = 1; i <= count; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = `${label} ${i}`;
            levelEl.appendChild(option);
        }
        levelEl.disabled = false;
    } else {
        levelEl.disabled = true;
        generateBtn.disabled = true;
    }
});

levelEl.addEventListener('change', function () {
    generateBtn.disabled = !this.value;
});

// For now, placeholder for generating timetable
const form = document.getElementById('timetableForm');
form.addEventListener('submit', function (e) {
    e.preventDefault();
    document.getElementById('generatedTimetable').classList.remove('hidden');
    document.getElementById('timetableContainer').innerHTML = '<p>[ Timetable Preview Will Be Shown Here ]</p>';
});