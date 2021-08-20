import React, { useEffect } from "react";


function CourseInspector(props) {
    console.log("init CourseInspector", props);

    /**
     * Builds the jsx for each prereq course list item
     * @param {Course} props
     * @returns
     */
    const prereqItems = (props) => {
        console.log("show prereqs");
        if (
            props.selectedCourse &&
            Array.isArray(props.selectedCourse.prerequisites) &&
            props.selectedCourse.prerequisites.length > 0
        ) {
            return props.selectedCourse.prerequisites.map((course, key) => {

                return (
                    <div
                        key={key}
                        className="related-course-row text-gray-700"
                    >
                        {course.abbreviation}
                    </div>
                );
            });
        } else {
            return <div className="related-course-row text-gray-700">None</div>
        }
    };

    /**
     * Builds the jsx for each concurrent course list item
     * @param {Course} props
     * @returns
     */
    const concurrentItems = (props) => {
        console.log("show concurrents");
        if (
            props.selectedCourse &&
            Array.isArray(props.selectedCourse.concurrents) &&
            props.selectedCourse.concurrents.length > 0
        ) {
            return props.selectedCourse.concurrents.map((course, key) => {

                return (
                    <div
                        key={key}
                        className="related-course-row text-gray-700"
                    >
                        {course.abbreviation}
                    </div>
                );
            });
        } else {
            return <div className="related-course-row text-gray-700">None</div>
        }
    };

    /**
     * Builds the jsx for each enabled course list item
     * @param {Course} props
     * @returns
     */
    const openItems = (props) => {
        console.log("show opened courses");
        if (
            props.selectedCourse &&
            Array.isArray(props.selectedCourse.childCourses) &&
            props.selectedCourse.childCourses.length > 0
        ) {
            return props.selectedCourse.childCourses.map((course, key) => {

                return (
                    <div
                        key={key}
                        className="related-course-row text-gray-700"
                    >
                        {course.abbreviation}
                    </div>
                );
            });
        } else {
            return <div className="related-course-row text-gray-700">None</div>
        }
    };

    /**
     * Builds the jsx for each enabled course list item
     * @param {Course} props
     * @returns
     */
    const professors = (props) => {
        if (
            props.selectedCourse &&
            props.selectedCourse.professors.length > 0
        ) {
            return props.selectedCourse.professors.map((prof, key) => {

                return (
                    <div
                        key={key}
                        className="related-course-row text-gray-700"
                    >
                        {prof.name}
                    </div>
                );
            });
        } else {
            return <div className="related-course-row text-gray-700">Unkown</div>
        }
    };

    return (
        <div className="left">
            <div id="inspector-selected-course">
                <div className="inspector-selected-label">{props.selectedCourse
                    ? props.selectedCourse.abbreviation
                    : "Select Course"}
                    </div>
                <div className={props.selectedCourse && props.selectedCourse.isCompleted ? "inspector-indicator completed" : "inspector-indicator"}></div>
            </div>
            <div className="inspector-selected-area grid grid-cols-2 gap-4">
                <div className="container">
                    <div className="inspector-row grid grid-cols2 gap-2">
                        <div className="inspector-data-label text-gray-500">
                            Title:
                        </div>
                        <div className="inspector-data-value text-gray-700">
                            {props.selectedCourse
                                ? props.selectedCourse.title
                                : ""}
                        </div>
                    </div>
                    <div className="inspector-row grid grid-cols2 gap-2">
                        <div className="inspector-data-label text-gray-500">
                            Description:
                        </div>
                        <div className="inspector-data-value text-gray-700">
                            {props.selectedCourse
                                ? props.selectedCourse.description
                                : ""}
                        </div>
                    </div>
                </div>
                <div className="container">
                    <div className="related-course-wrapper">
                        <div className="inspector-data-label text-gray-500">
                            Prerequisites:
                        </div>
                        <div id="inspector-related-course">
                            {prereqItems(props)}
                        </div>
                    </div>
                    <div className="related-course-wrapper">
                        <div className="inspector-data-label text-gray-500">
                            Concurrent Courses:
                        </div>
                        <div id="inspector-related-course">
                            {concurrentItems(props)}
                        </div>
                    </div>
                    <div className="related-course-wrapper">
                        <div className="inspector-data-label text-gray-500">
                            Opens Courses:
                        </div>
                        <div id="inspector-related-course">
                            {openItems(props)}
                        </div>
                    </div>
                    <div className="related-course-wrapper">
                        <div className="inspector-data-label text-gray-500">
                            Professors:
                        </div>
                        <div id="inspector-related-course">
                            {professors(props)}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default CourseInspector;
