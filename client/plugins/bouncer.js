import Bouncer from '~/assets/js/Bouncer';

export default ({$auth}, inject) => {
    const bouncer = new Bouncer($auth.user);

    inject('bouncer', bouncer)
}