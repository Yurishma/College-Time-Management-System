const departmentEl = document.getElementById('department');
const semesterBlock = document.getElementById('semesterBlock');
const yearBlock = document.getElementById('yearBlock');
const subjectBlock = document.getElementById('subjectBlock');
const semesterCheckboxes = document.getElementById('semesterCheckboxes');
const yearCheckboxes = document.getElementById('yearCheckboxes');
const subjectsEl = document.getElementById('subjects');
const submitBlock = document.getElementById('submitBlock');

const subjectMap = {
  BCA: {
    1: ["Society & Technology", "Mathematics I", "Digital Logic", "English", "Computer Fundamentals"],
    2: ["C Programming", "Mathematics II", "Microprocessor", "Accounting", "English II"],
    3: ["Java", "Web Technology", "System Analysis and Design", "Data Structure", "Statistics"],
    4: ["Operating System", "Numerical Methods", "Software Engineering", "Scripting", "Database"],
    5: [".NET", "MIS", "Computer Networking", "Management", "Computer Graphics"],
    6: ["Mobile Programming", "Distributed System", "Applied Economics", "Advance Java", "Network programming"],
    7: ["Cyber Law and ethics", "Cloud Computing", "Elective I", "Elective II"],
    8: ["Operations Research", "Elective III", "Elective IV"]
  },
  BBM: {
    1: ["Business Mathematics I", "English I", "Microeconomics", "Sociology for Business", "Principles of Management"],
    2: ["Business Mathematics II", "English II", "Financial Accounting", "Macroeconomics", "Psychology"],
    3: ["Basic Finance", "Business Communication", "Computer Based Financial Accounting", "Business statistics", "Nepalese society and politics"],
    4: ["Accounting for decision making", "Taxation", "Financial Mgmt", "Research Methods", "Human Resource Management"],
    5: ["Focus Area Course I", "Fundamentals of Marketing", "Introduction to operation management", "Legal Environment of Business", "Organizational Behavior"],
    6: ["Database Mgmt", "Focus Area II", "Business Environment in Nepal", "Introduction to International Business"],
    7: ["Business Ethics", "E-commerce", "Elective Course I", "Focus Area III", "Focus Area IV"],
    8: ["Business Strategy", "Elective Areas", "Elective III", "Elective II", "Focus Area Course V", "Focus Area Courses"]
  },
  BBS: {
    1: ["Business English", "Business Statistics", "Financial Accounting", "Microeconomics for Business", "Principles of Mgmt"],
    2: ["Organizational Behavior", "Cost Accounting", "Business Communication", "Fundamentals of Financial Management", "Macro Economics"],
    3: ["Business Environment and Strategy", "Business Law", "Foundation of Financial Systems", "Marketing", "Taxation"],
    4: ["Business Research Methods", "Entrepreneurship", "Concentration Areas"]
  }
};

departmentEl.addEventListener('change', function () {
  const dept = this.value;
  semesterBlock.classList.add('hidden');
  yearBlock.classList.add('hidden');
  subjectBlock.classList.add('hidden');
  submitBlock.classList.add('hidden');
  semesterCheckboxes.innerHTML = "";
  yearCheckboxes.innerHTML = "";
  subjectsEl.innerHTML = "";

  if (dept === 'BCA' || dept === 'BBM') {
    semesterBlock.classList.remove('hidden');
    generateCheckboxes(8, 'semester');
  } else if (dept === 'BBS') {
    yearBlock.classList.remove('hidden');
    generateCheckboxes(4, 'year');
  }
});

function generateCheckboxes(count, type) {
  const container = type === 'semester' ? semesterCheckboxes : yearCheckboxes;
  for (let i = 1; i <= count; i++) {
    const label = document.createElement('label');
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.name = `${type}s[]`;
    checkbox.value = i;
    checkbox.addEventListener('change', () => updateSubjectDurations(type));
    label.appendChild(checkbox);
    label.append(` ${type.charAt(0).toUpperCase() + type.slice(1)} ${i}`);
    container.appendChild(label);
  }
}

function updateSubjectDurations(type) {
  const dept = departmentEl.value;
  const selected = Array.from(document.querySelectorAll(`input[name="${type}s[]"]:checked`)).map(cb => cb.value);
  subjectsEl.innerHTML = "";

  if (selected.length === 0) {
    subjectBlock.classList.add('hidden');
    submitBlock.classList.add('hidden');
    return;
  }

  subjectBlock.classList.remove('hidden');
  submitBlock.classList.remove('hidden');

  selected.forEach(level => {
    const subjects = subjectMap[dept]?.[level];
    if (subjects) {
      const title = document.createElement('h4');
      title.textContent = `${type.charAt(0).toUpperCase() + type.slice(1)} ${level}`;
      subjectsEl.appendChild(title);

      subjects.forEach(subject => {
        const row = document.createElement('div');
        row.style.marginBottom = "10px";

        const label = document.createElement('label');
        label.textContent = subject;
        label.style.display = "block";
        label.style.fontWeight = "bold";
        row.appendChild(label);

        // Duration input
        const durationInput = document.createElement('input');
        durationInput.type = 'number';
        durationInput.name = `duration[${type}][${level}][${subject}]`;
        durationInput.min = 30;
        durationInput.placeholder = "Duration (min)";
        durationInput.required = true;
        durationInput.style.marginRight = "10px";

        // Frequency input
        const freqInput = document.createElement('input');
        freqInput.type = 'number';
        freqInput.name = `frequency[${type}][${level}][${subject}]`;
        freqInput.min = 1;
        freqInput.placeholder = "Frequency (/week)";
        freqInput.required = true;

        row.appendChild(durationInput);
        row.appendChild(freqInput);
        console.log("Adding subject row for", subject);

        subjectsEl.appendChild(row);
      });
    }
  });
}
