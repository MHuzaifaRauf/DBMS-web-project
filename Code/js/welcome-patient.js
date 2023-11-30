async function welcomePatient(name, email) {

    const html = `
      <div style="background-color: #f6f8fc; padding: 10px; text-align: center;">
        <h3>Welcome to BestLab</h3>
        <p>Hello ${name},</p>
        <p>We are excited to welcome you to BestLab! Thank you for choosing us for your medical needs.</p>
        <p>If you have any questions or need assistance, feel free to reach out to us.</p>
        <a href="https://www.example.com" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none;">Visit Our Website</a>
      </div>
    `;
    try {
    await fetch(
            // "http://localhost:8000/send-report" ||
              "https://wide-eyed-fatigues-fawn.cyclic.app/send-report",
            {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({ email, html }),
            }
          );
      console.log("Email sent successfully!");
    } catch (e) {
      console.log("Error sending email:", e.message);
    }
  }