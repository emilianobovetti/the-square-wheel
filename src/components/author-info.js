import React from 'react';
import { StaticQuery, graphql } from 'gatsby';

const authorQuery = graphql`
  query AuthorQuery {
    site {
      siteMetadata {
        author
      }
    }
  }
`;

const AuthorInfo = _props =>
  <StaticQuery
    query={authorQuery}
    render={data =>
      <p><strong>{data.site.siteMetadata.author}</strong></p>
    }
  />;

export default AuthorInfo;
