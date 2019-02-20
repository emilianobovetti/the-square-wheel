import React from 'react';
import { graphql } from 'gatsby';
import PropTypes from 'prop-types';
import styled from 'styled-components';

import rhythm from '../utils/typography/rhythm';
import Layout from '../components/layout';
import SEO from '../components/seo';

const GiphyResponsiveWrapper = styled.div`
  position: relative;
  padding-bottom: 56.8%;
  margin-top: ${rhythm(5 / 2)};
  height: 0;

  @media screen and (min-width: 568px) {
  }

  @media screen and (min-width: 768px) {
  }
`;

const GiphyIframe = styled.iframe`
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
`;

const NotFoundPage = ({ data }) =>
  <Layout title={data.site.siteMetadata.title}>
    <SEO lang="en" title="404: Not Found" />
    <h1>404: Not Found</h1>
    <GiphyResponsiveWrapper>
      <GiphyIframe
        src="https://giphy.com/embed/joV1k1sNOT5xC"
        frameBorder="0"
        allowFullScreen>
      </GiphyIframe>
    </GiphyResponsiveWrapper>
  </Layout>;

NotFoundPage.propTypes = {
  data: PropTypes.object,
};

export default NotFoundPage;

export const pageQuery = graphql`
  query {
    site {
      siteMetadata {
        title
      }
    }
  }
`;
