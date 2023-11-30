

async function forwardReports(
  reportID,
  pEmail,
  tStatus,
  tName,
  pName,
  dName,
  dEmail,
  pId,
  dId,
  manager_id
) {


  const { value: formValues } = await Swal.fire({
    title: "Send Report",
    html: `
      <label for="patientEmail">Patient Email</label>
      <input type="email" id="patientEmail" class="swal2-input" disabled value="${
        pEmail || ""
      }" placeholder="Enter patient's email">
      <label for="doctorEmail">Doctor Email</label>
      <input type="email" id="doctorEmail" class="swal2-input" disabled value="${
        dEmail || ""
      }" placeholder="Enter doctor's email">
    `,
    confirmButtonColor: "#001F3F",
    showCancelButton: true,
    preConfirm: () => {
      return {
        patientEmail: document.getElementById("patientEmail").value,
        doctorEmail: document.getElementById("doctorEmail").value,
      };
    },
    focusConfirm: false,
    confirmButtonText: "Send",
    cancelButtonText: "Cancel",
  });

  if (!formValues) {
    // User clicked "Cancel" or provided invalid input, so exit the function.
    return;
  }
  const patientEmail = pEmail || formValues.patientEmail;
  const doctorEmail = dEmail || formValues.doctorEmail;
  // User clicked "Send," so show a loading message.
  Swal.fire({
    icon: "info",
    title: "Sending Report",
    text: "Please wait while the report is being sent...",
    allowOutsideClick: false,
    showConfirmButton: false,
  });

  const statusColor = tStatus === "positive" ? "#28a745" : "#dc3545";
  const statusText = tStatus.charAt(0).toUpperCase() + tStatus.slice(1);

  const Phtml = `
    <div style="background-color: #f6f8fc; padding: 10px; text-align: center;">
      <h3>BestLab Report Result</h3>
      <p class="status"><i>${pName.toUpperCase()}</i> your Test report ${tName} status is <b style="color: ${statusColor};">${statusText}</b></p>
      <a href="http://bestlab.000.pe" style="display: inline-block; margin-top: 20px; background-color: #0C7075; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View Report</a>
    </div>
  `;
  const Dhtml = `
    <div style="background-color: #f6f8fc; padding: 10px; text-align: center;">
      <h3>BestLab Report Result</h3>
      <p class="status">Dr.${dName.toUpperCase()} your Patient ${pName}'s Test ${tName} report status 
      is <b style="color: ${statusColor};">${statusText}</b></p>
     </br>
      <span  style="display: inline-block; margin-top: 20px;  
      color: black; padding: 10px 20px; text-decoration: none;">You
       may contact ${pName.toUpperCase()} soon.</span>
    </div>
  `;

  try {
    // Send the first email
    const pResponse = await fetch(
      "https://wide-eyed-fatigues-fawn.cyclic.app/send-report",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email: patientEmail, html: Phtml }),
      }
    );

    // // Pause execution for 2 seconds
    // await new Promise((resolve) => setTimeout(resolve, 3000));

    // // Send the second email after the pause
    const dResponse = await fetch(
      "https://wide-eyed-fatigues-fawn.cyclic.app/send-report",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email: doctorEmail, html: Dhtml }),
      }
    );

    // Handle responses if needed
    const pJson = await pResponse.json(); // Await the promise
    const dJson = await dResponse.json(); // Await the promise

    if (pJson || dJson) {
      
      const sendReportResponse = await fetch("send-report.php", {
        method: "POST",
        body: JSON.stringify({
          reportID,
          manager_id,
          pId,
          dId,
          patientEmail,
          doctorEmail,
        }),
        headers: {
          "Content-Type": "application/json",
        },
      });

      const sendReportData = (await sendReportResponse.text()).split('{"')[1];
      
      if (sendReportData?.includes("true")) {
        Swal.fire({
          icon: "success",
          title: "Success",
          text: "The reports have been sent successfully!",
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to send reports. Please try again",
        });
      }
    } else {
      await Swal.fire({
        icon: "error",
        title: "Error",
        text: "Failed to forward reports. Please try again later.",
      });
    }
  } catch (e) {
    // Handle errors
    console.error("Error:", e);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "An error occurred while sending the reports. Please try again later.",
    });
  }
}
