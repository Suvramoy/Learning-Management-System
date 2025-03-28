
# Learning Management System (LMS)  

This project is a web-based Learning Management System (LMS) designed to facilitate efficient interactions between instructors and students. The platform provides features for creating quizzes, managing assignments, and conducting surveys, along with respective dashboards for instructors and students.

## Features  

### Instructor Dashboard  
1. **Create Quizzes**  
   - Instructors can select questions from a predefined list of questions.  
   - Questions can be modified or new questions can be added to the list for the subjects taught by the instructor.  
   - The instructor can specify multiple-choice, true/false, or short-answer question types.  
   - Quizzes allow multiple attempts by students, with responses stored for each attempt.  

2. **Manage Assignments**  
   - Instructors can allocate assignments and set due dates.  
   - Late submissions are not allowed.  
   - Instructors can grade assignments submitted by students.  

3. **Grade Quiz Attempts**  
   - Instructors can view each student’s quiz attempts and assign grades to individual attempts.  

### Student Dashboard  
1. **Enroll in Courses**  
   - Students can browse and enroll in available courses.  

2. **Attempt Quizzes**  
   - Students can take quizzes for the courses they are enrolled in.  
   - Multiple attempts are allowed, with all responses stored in the database.  

3. **Submit Assignments**  
   - Students can upload assignments in specified formats (e.g., `.pdf`, `.txt`, `.ppt`).  
   - Assignments must be submitted before the deadline.  

4. **View Grades**  
   - Students can view grades for quizzes and assignments.  

## Technologies Used  
- **Backend**: PHP  
- **Database**: MySQL (with normalization for optimized design)  
- **Frontend**: HTML, CSS, JavaScript


## Database Design  

The database has been rigorously tested and optimized through normalization to handle all functionalities efficiently and eliminate redundancy. 
