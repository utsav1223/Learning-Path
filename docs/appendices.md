# A. Appendices

Appendices may be used to provide additional and helpful information for the Software Requirements Specification. The information in these appendices is intended to clarify the software project, its supporting documents, and its design references.

Unless explicitly stated otherwise, the information contained within these appendices is supporting documentation and is not considered part of the SRS's overall set of requirements. Requirements should be taken from the main SRS sections only.

## A.1 Appendix 1: Initial Conceptual Document

The initial concept of the project is a personalized learning website that helps learners select a learning goal, complete onboarding, take a skill assessment, and receive a personalized dashboard and learning roadmap.

The system supports the following major concepts:

| Concept | Description |
| --- | --- |
| User Authentication | Learners can register, verify email, log in, log out, reset passwords, or use Google login. |
| Onboarding | Learners provide education level, skill level, interests, target role, preferred language, study time, learning pace, and other preferences. |
| Skill Assessment | The system selects assessment questions based on the learner's goal and recommended technologies. |
| Performance Analysis | The system calculates score, percentage, weak areas, strong areas, and topic-wise performance. |
| Personalized Dashboard | The dashboard displays learner progress, assessment analysis, recommended modules, and roadmap-related information. |
| AI Roadmap Generation | The system can use Gemini API to generate a personalized learning roadmap. If Gemini is unavailable, a fallback roadmap is generated locally. |
| Course and Category APIs | The system provides API endpoints for viewing and managing courses and categories. |

This appendix is provided as project background and design context. It does not introduce additional requirements beyond those defined in the main SRS.

## A.2 Appendix 2: Supporting Design Documents

The following supporting documents may be referenced with the SRS:

| Document | Purpose |
| --- | --- |
| Data Flow Diagram | Shows how data moves between the learner, system processes, database, and external services. |
| Database Migrations | Define the database structure for users, profiles, courses, categories, assessments, answers, cache, and jobs. |
| Route Definitions | Describe the available web and API endpoints of the system. |
| Controller Logic | Explains how requests are validated, processed, saved, and returned to the user. |
| Environment Configuration | Stores configuration values for database, mail, Google OAuth, Gemini API, session, cache, and application settings. |

The Data Flow Diagram is available in `docs/data-flow-diagram.md`.

These documents are useful for understanding the system design and implementation. They support the SRS but should not be interpreted as independent functional requirements unless a requirement is explicitly stated in the main SRS.
