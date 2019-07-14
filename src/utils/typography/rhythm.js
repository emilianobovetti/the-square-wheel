import typography from '../typography';
import compose from '../compose';
import serialize from './serialize';

export default compose(typography.rhythm, serialize);
