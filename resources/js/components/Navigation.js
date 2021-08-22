import React from 'react';
import ReactDOM from 'react-dom';

class Navigation extends React.Component {

    constructor(props){
        super(props);

        const navItems = JSON.parse(this.props.nav);
        if (! JSON.parse(this.props.isSuperUser)) {
            navItems.pop();
        }

        this.nav = {
            'links' : navItems
        }

    };


    render(){

        const navLinks = this.nav.links.map( (link, x) => {
            return (
                <li className="mr-6" key={x}>
                    <a className={`${window.location.href.indexOf(link.uri) === -1 || window.location.href.indexOf("/courses/") !== -1
                        ? 'text-gray-500 hover:text-blue-800' : 'text-blue-800 font-medium cursor-text'}`}
                       href={`${window.location.href.indexOf(link.uri) === -1 || window.location.href.indexOf("/courses/") !== -1
                           ? `${link.uri}` : '#'}`}>{link.name}</a>
                </li>
            )
        });

        return (
            <ul className="flex px-6 py-2 border-b-2 mb-4">
                {navLinks}
            </ul>
        );
    }
}

export default Navigation;

if (document.getElementById('navigation')) {

    // get props
    const data = document.getElementById('navigation').getAttribute('data-nav');
    const isSuperUser = document.getElementById('navigation').getAttribute('data-super')

    ReactDOM.render(<Navigation nav={data} isSuperUser={isSuperUser}/>, document.getElementById('navigation'));
}
