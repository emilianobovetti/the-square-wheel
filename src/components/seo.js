import React from 'react';
import PropTypes from 'prop-types';
import Helmet from 'react-helmet';
import { StaticQuery, graphql } from 'gatsby';

const detailsQuery = graphql`
  query DefaultSEOQuery {
    site {
      siteMetadata {
        title
        description
        author
      }
    }
  }
`;

const SEO = ({ description, lang, meta, keywords, title }) =>
  <StaticQuery
    query={detailsQuery}
    render={data => {
      const metaDescription = description || data.site.siteMetadata.description;
      const metaTitle = data.site.siteMetadata.title;
      const fullTitle = title ? `${title} | ${metaTitle}` : metaTitle;

      return (
        <Helmet
          htmlAttributes={{ lang }}
          title={fullTitle}
          meta={[
            {
              name: 'description',
              content: metaDescription,
            },
            {
              property: 'og:title',
              content: title,
            },
            {
              property: 'og:description',
              content: metaDescription,
            },
            {
              property: 'og:type',
              content: 'website',
            },
          ]
            .concat(meta)
            .concat(
              keywords.length > 0
                ? {
                  name: 'keywords',
                  content: keywords.join(', '),
                }
                : []
            )
          }
        />
      );
    }}
  />;

SEO.defaultProps = {
  meta: [],
  keywords: [],
};

SEO.propTypes = {
  description: PropTypes.string,
  lang: PropTypes.string.isRequired,
  meta: PropTypes.array,
  keywords: PropTypes.arrayOf(PropTypes.string),
  title: PropTypes.string,
};

export default SEO;
