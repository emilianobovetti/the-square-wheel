import React from 'react';
import { Link, graphql } from 'gatsby';
import PropTypes from 'prop-types';
import styled from 'styled-components';

import Layout from '../components/layout';
import SEO from '../components/seo';
import rhythm from '../utils/typography/rhythm';
import scale from '../utils/typography/scale';

const DateBox = styled.p`
  ${scale(-1 / 5)}
  margin-bottom: ${rhythm(1)};
  margin-top: ${rhythm(-1)};
`;

const ArticleNavigation = styled.ul`
  overflow: auto;
  list-style: none;
  padding: 0;
  margin: 0;
`;

const LeftLinkWrapper = styled.li`
  float: left;
`;

const RightLinkWrapper = styled.li`
  float: right;
`;

const BlogPostTemplate = props => {
  const post = props.data.markdownRemark;
  const { title, lang } = post.frontmatter;
  const { previous, next } = props.pageContext;

  return (
    <Layout>
      <SEO lang={lang} title={title} description={post.excerpt} />
      <h1>{title}</h1>
      <DateBox>{post.frontmatter.date}</DateBox>
      <div dangerouslySetInnerHTML={{ __html: post.html }} />
      <ArticleNavigation>
        <LeftLinkWrapper>
          {previous && (
            <Link to={previous.fields.slug} rel="prev">
              ← {previous.frontmatter.title}
            </Link>
          )}
        </LeftLinkWrapper>
        <RightLinkWrapper>
          {next && (
            <Link to={next.fields.slug} rel="next">
              {next.frontmatter.title} →
            </Link>
          )}
        </RightLinkWrapper>
      </ArticleNavigation>
    </Layout>
  );
};

BlogPostTemplate.propTypes = {
  data: PropTypes.object,
  pageContext: PropTypes.object,
};

export default BlogPostTemplate;

export const pageQuery = graphql`
  query BlogPostBySlug($slug: String!) {
    site {
      siteMetadata {
        author
      }
    }
    markdownRemark(fields: { slug: { eq: $slug } }) {
      id
      excerpt(pruneLength: 160)
      html
      frontmatter {
        title
        lang
        date(formatString: "MMMM DD, YYYY")
      }
    }
  }
`;
