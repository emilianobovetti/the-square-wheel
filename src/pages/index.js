import React from 'react';
import { graphql } from 'gatsby';
import PropTypes from 'prop-types';

import PostPreview from '../components/post-preview';
import Layout from '../components/layout';
import SEO from '../components/seo';

const BlogIndex = ({ data }) =>
  <Layout title={data.site.siteMetadata.title}>
    <SEO lang="en" />

    {data.allMarkdownRemark.edges.map(PostPreview)}
  </Layout>;

BlogIndex.propTypes = {
  data: PropTypes.object,
};

export default BlogIndex;

export const pageQuery = graphql`
  query {
    site {
      siteMetadata {
        title
      }
    }
    allMarkdownRemark(sort: { fields: [frontmatter___date], order: DESC }) {
      edges {
        node {
          excerpt(format: HTML)
          fields {
            slug
          }
          frontmatter {
            date(formatString: "MMMM DD, YYYY")
            title
          }
        }
      }
    }
  }
`;
