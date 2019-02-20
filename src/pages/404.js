import React from 'react';
import { graphql } from 'gatsby';
import PropTypes from 'prop-types';
import styled from 'styled-components';

import Layout from '../components/layout';
import SEO from '../components/seo';

const GiphyResponsiveWrapper = styled.div`
  width:100%;
  height:0;
  padding-bottom:57%;
  position:relative;
`;

const GiphyIframe = styled.iframe`
  position:absolute;
`;

const NotFoundPage = ({ data }) =>
  <Layout title={data.site.siteMetadata.title}>
    <SEO lang="en" title="404: Not Found" />
    <h1>404 Not Found</h1>
    <GiphyResponsiveWrapper>
      <GiphyIframe
        src="https://giphy.com/embed/joV1k1sNOT5xC"
        width="100%"
        height="100%"
        frameBorder="0"
        class="giphy-embed"
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
