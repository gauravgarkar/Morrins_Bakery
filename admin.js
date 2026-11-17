const submissions = [
  {
    name: "sample1",
    email: "sample1@gmail.com",
    phone: "1234567890",
    message: "This is the first sample message."
  },
  {
    name: "sample2",
    email: "sample2@gmail.com",
    phone: "2345678912",
    message: "This is the second sample message."
  }
];

const submissionRows = document.querySelector("#submissionRows");
const submissionCount = document.querySelector("#submissionCount");

submissionRows.replaceChildren();

for (const entry of submissions) {
  const row = document.createElement("tr");

  const values = [
    entry.name,
    entry.email,
    entry.phone,
    entry.message
  ];

  for (const val of values) {
    const cell = document.createElement("td");
    cell.textContent = val;
    row.appendChild(cell);
  }

  submissionRows.appendChild(row);
}

submissionCount.textContent = submissions.length + " submissions";