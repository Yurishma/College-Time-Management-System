
function addAssignment() {
    const container = document.getElementById("assignmentSection");
    const div = document.createElement("div");
    div.classList.add("assignment-pair");
    div.innerHTML = `
        <textarea name="subjects[]" rows="2" placeholder="Subject"></textarea>
        <select name="semesters[]">
            ${[...Array(8).keys()].map(i => `<option value="semester-${i+1}">Semester ${i+1}</option>`).join('')}
        </select>
    `;
    container.appendChild(div);
}

