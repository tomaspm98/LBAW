@extends('layouts.app')

@section('content')
<section id="about">
    <div class="platform-description main-containers">
            
        <div class="image-container">
            <img class="left-picture" src="{{ url('/pictures/about/about-us-left-picture.jpg') }}" alt="Image that alludes to communication online and interaction">
        </div>
        <div class="text-description">
            <h2>Our Platform</h2>
            <p class="description">QueryStack is a platform for technology enthusiasts, professionals, and learners to share their knowledge and solve their problems.</p>
            <p class="description">QueryStack allows users to ask, answer, vote, comment, and edit questions and answers related to various topics in technology, such as programming languages, frameworks, tools, algorithms, data structures, design patterns, etc. QueryStack also provides features such as tags, badges, reputation points, and leaderboards to categorize, reward, and rank the users and their contributions.</p>
        </div>
    </div>
    <div class="core-values main-containers">
        <h2>
            <span>Our core values</span>
        </h2>
        <div class="core-values-list">
            <div class="core-value-element">
                <h3>Adopt a customer-first mindset</h3>
                <p>Authentically serve our customers by empowering, listening, and collaborating with our fellow Stackers.</p>
            </div>
            <div class="core-value-element">
                <h3>Be flexible and inclusive</h3>
                <p>We do our best work when a diverse group of people collaborate in an environment of respect and trust. Create space for different voices to be heard, and allow flexibility in how people work. </p>
            </div>
            <div class="core-value-element">
                <h3>Be transparent</h3>
                <p>Communicate openly and honestly, both inside and outside the company. Encourage transparency from others by being empathetic, reliable, and acting with integrity. </p>
            </div>
            <div class="core-value-element">
                <h3>Learn, share, grow</h3>
                <p>Adopt a growth mindset. Be curious and eager to learn. Aim for ethical, sustainable, long-term growth, both personally and in the company.  </p>
            </div>
        </div>
    </div>
    <div class="platform-team main-containers"  >
        <h2>
            <span>Our team</span>
        </h2>
        <div class="team-members">
            <div class="team-member">
                <div class="team-member-image">
                    <img src="{{ url('/pictures/about/team_member_ricardo.png') }}" alt="Ricardo Peralta">
                </div>
                <div class="team-member-description">
                    <h3>Ricardo Peralta</h3>
                    <p>
                        Ricardo is a dedicated programming student with a passion for problem-solving. He enjoys exploring various programming languages and is always eager to learn new concepts.
                    </p>
                </div>
            </div>
            <div class="team-member">
                <div class="team-member-image">
                    <img src="{{ url('/pictures/about/team_member_goncalo.png') }}" alt="Gonçalo">
                </div>
                <div class="team-member-description">
                    <h3>Gonçalo</h3>
                    <p>
                        Gonçalo, a programming enthusiast, approaches coding with curiosity and determination. He is committed to continuous learning and enjoys the process of turning ideas into functional and meaningful applications.
                    </p>
                </div>
            </div>
            <div class="team-member">
                <div class="team-member-image">
                    <img src="{{ url('/pictures/about/team_member_tomas.png') }}" alt="Tomas">
                </div>
                <div class="team-member-description">
                    <h3>Tomas</h3>
                    <p>
                        Tomás, a programming student, finds joy in creating logical and efficient solutions. He believes in the power of coding to bring ideas to life and is enthusiastic about embracing different coding challenges.
                    </p>
                </div>       
            </div>
            <div class="team-member">
                <div class="team-member-image">
                    <!-- <img src="{{ url('/pictures/about/team_member_antonio.png') }}" alt="António"> -->
                </div>
                <div class="team-member-description">
                    <h3>António</h3>
                    <p>
                        António is a diligent programming enthusiast who values collaboration and knowledge-sharing. He is keen on understanding the intricacies of code and applying them to real-world scenarios.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="platform-contacts  main-containers" id="contacts">
        <h2>Contact Us</h2>
        <p class="contacts">If you have any questions or feedback, feel free to reach out to us.</p>
        <ul class="contacts">
            <li>Email: up202206392@up.pt</li>
            <li>Email: info@example.com</li>
            <li>Email: info@example.com</li>
            <li>Email: info@example.com</li>
            <li>Physical support: FEUP - Porto, Portugal </li>
        </ul>
    </div>

    <div class="platform-faqs main-containers" id="faqs">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-section">
        <span class="faq-question">How do I register for an account? <span class="arrow" onclick="toggleAnswer(this)">&#9654;</span></span>
        <div class="faq-answer">
            <p>To register, click on the 'Register' button on the top right corner. Fill in your details in the registration form and follow the instructions to complete the process.</p>
        </div>
    </div>
    <div class="faq-section">
        <span class="faq-question">How do I ask a question? <span class="arrow" onclick="toggleAnswer(this)">&#9654;</span></span>
        <div class="faq-answer">
            <p>To ask a question, click on the 'Create Question' button on the homepage. Make sure your question is clear and understandable so that others can answer it inside the context.</p>
        </div>
    </div>
    <div class="faq-section">
        <span class="faq-question"> What kind of questions can I ask? <span class="arrow" onclick="toggleAnswer(this)">&#9654;</span></span>
        <div class="faq-answer">
            <p>You can ask any question that follow community guidelines. Avoid offensive, inappropriate, or overly personal content. Check the tags we have available for the questions.</p>
        </div>
    </div>
    <div class="faq-section">
        <span class="faq-question">How do I answer a question? <span class="arrow" onclick="toggleAnswer(this)">&#9654;</span></span>
        <div class="faq-answer">
            <p>To answer, click on the question and write your answer in th provided space. Make sure it is informative, respectful, and respects to our community guidelines.</p>
        </div>
    </div>
    <div class="faq-section">
        <span class="faq-question">How can I report inappropriate content or behavior? <span class="arrow" onclick="toggleAnswer(this)">&#9654;</span></span>
        <div class="faq-answer">
            <p>If you see content or behavior that violates our community guidelines, please use the 'Report' button associated with the content and fill the reason and an optional text to provide more details.</p>
        </div>
    </div>
    <div class="faq-section">
        <span class="faq-question">How does the voting system work? <span class="arrow" onclick="toggleAnswer(this)">&#9654;</span></span>
        <div class="faq-answer">
            <p>Users can upvote or downvote questions, answers and comments based on their accuracy and helpfulness. Voting increases your personal score.</p>
        </div>
    </div>
</div>
</section>

<script>
    function toggleAnswer(arrow) {
        var answer = arrow.parentNode.nextElementSibling;
        if (answer.style.display === "block") {
            answer.style.display = "none";
            arrow.classList.remove("rotate");
        } else {
            answer.style.display = "block";
            arrow.classList.add("rotate");
        }
    }
</script>

@endsection