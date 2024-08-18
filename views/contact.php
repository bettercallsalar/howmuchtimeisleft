<div class="container contact-page">
    <div class="row">
        <div class="col-md-12">
            <h1>Contact Us</h1>
            <p>We would love to hear from you! Whether you have a question, feedback, or just want to share your experience, feel free to reach out to us.</p>

            <h2>Get in Touch</h2>
            <form action="index.php?Controller=contact&Action=submitContactForm" method="POST">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>

            <h2>Other Ways to Reach Us</h2>

        </div>
    </div>
</div>