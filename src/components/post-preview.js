import React from 'react';
import PropTypes from 'prop-types';
import styled from 'styled-components';
import { Link } from 'gatsby';

import rhythm from '../utils/typography/rhythm';

const PostTitle = styled.h3`
  margin-bottom: ${rhythm(1 / 4)};
`;

const PostTitleLink = styled(Link)`
  box-shadow: none;
`;

const PostPreview = ({ node }) => (
  <div key={node.fields.slug}>
    <PostTitle>
      <PostTitleLink to={node.fields.slug}>
        {node.frontmatter.title || node.fields.slug}
      </PostTitleLink>
    </PostTitle>
    <small>{node.frontmatter.date}</small>
    <div dangerouslySetInnerHTML={{ __html: node.excerpt }} />
  </div>
);

PostPreview.propTypes = {
  node: PropTypes.object,
};

export default PostPreview;
