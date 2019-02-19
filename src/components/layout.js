import React from 'react';
import PropTypes from 'prop-types';
import styled from 'styled-components';
import { Link } from 'gatsby';

import rhythm from '../utils/typography/rhythm';
import logoSrc from '../../content/assets/logo.svg';
import homeLogoSrc from '../../content/assets/home.svg';

const TitleLink = styled(Link)`
  color: inherit;
`;

const PageWrapper = styled.div`
  background: linear-gradient(#f2f2f2 25%, white 50%);
  padding-top: ${rhythm(1)};
`;

const LogoWrapper = styled(Link)`
  margin: 0 ${rhythm(1)};
  float: left;
  width: 100px;

  @media screen and (min-width: 568px) {
    width: 140px;
  }

  @media screen and (min-width: 768px) {
    width: unset;
  }
`;

const LogoImg = styled.img`
  margin: 0;
  height: auto;
  width: 100%;
`;

const ContentWrapper = styled.div`
  margin: 0 auto;
  max-width: ${rhythm(24)};
  padding: 0 ${rhythm(3 / 4)};
`;

const MainWrapper = styled(ContentWrapper)`
  clear: both;

  @media screen and (min-width: 568px) {
    clear: none;
  }
`;

const HomeLogo = styled.img`
  height: ${rhythm(2)};
  width: ${rhythm(2)};
  float: right;
`;

const Footer = styled.footer`
  margin-top: ${rhythm(1.5)};
`;

const BlankTargetLink = ({ href, title, children }) =>
  <a href={href} title={title} target="_blank" rel="noopener noreferrer">
    {children}
  </a>;

const Ivan =
  <BlankTargetLink href="https://twitter.com/iprignano">
    Ivan
  </BlankTargetLink>;

const DaveGandy =
  <BlankTargetLink
    title="Dave Gandy"
    href="https://www.flaticon.com/authors/dave-gandy"
  >
    Dave Gandy
  </BlankTargetLink>;

const Flaticon =
  <BlankTargetLink
    title="Flaticon"
    href="https://www.flaticon.com/"
  >
    www.flaticon.com
  </BlankTargetLink>;

const CCLicense =
  <BlankTargetLink
    title="Creative Commons BY 3.0"
    href="http://creativecommons.org/licenses/by/3.0/"
  >
    CC 3.0 BY
  </BlankTargetLink>;

const Layout = ({ title, children }) =>
  <PageWrapper>
    <LogoWrapper to={'/'}>
      <LogoImg src={logoSrc} alt="The Square Wheel's logo" />
    </LogoWrapper>

    <ContentWrapper>
      {title
        ? <h1><TitleLink to={'/'}>{title}</TitleLink></h1>
        : <Link to={'/'}><HomeLogo src={homeLogoSrc} alt="Home" /></Link>
      }
    </ContentWrapper>

    <MainWrapper>
      <main>{children}</main>
      <Footer>
        <div>
          The Square Wheel&apos;s logo is made by {Ivan}
        </div>

        <div>
          Icons made by {DaveGandy} from {Flaticon} is licensed by {CCLicense}
        </div>
      </Footer>
    </MainWrapper>
  </PageWrapper>;

Layout.propTypes = {
  title: PropTypes.string,
  children: PropTypes.node,
};

BlankTargetLink.propTypes = {
  href: PropTypes.string,
  title: PropTypes.string,
  children: PropTypes.node,
};

export default Layout;
