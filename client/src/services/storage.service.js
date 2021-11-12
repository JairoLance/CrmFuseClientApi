const TOKEN_KEY = 'access_token'
const REFRESH_TOKEN_KEY = 'refresh_token'

const SESSIONS  = 'sessions';

const BODEGA    = 'session_bodega';
const IDBODEGA  = 'session_idbodega';

const USUARIO   = 'session_usuario';
const IDUSUARIO = 'session_idusuario';

const NOMBRE    = 'session_nombre';
const NIT       = 'session_nit';

const ROL       = 'session_rol';
const IDROL     = 'session_idrol';

/**
 * Manage the how Access Tokens are being stored and retreived from storage.
 *
 * Current implementation stores to localStorage. Local Storage should always be
 * accessed through this instace.
 * *https://medium.com/@zitko/structuring-a-vue-project-authentication-87032e5bfe16
**/
const TokenService = {
    getToken() {
        return localStorage.getItem(TOKEN_KEY)
    },

    saveToken(accessToken) {
        localStorage.setItem(TOKEN_KEY, accessToken)
    },

    removeToken() {
        localStorage.removeItem(TOKEN_KEY)
    },

    getRefreshToken() {
        return localStorage.getItem(REFRESH_TOKEN_KEY)
    },

    saveRefreshToken(refreshToken) {
        localStorage.setItem(REFRESH_TOKEN_KEY, refreshToken)
    },

    removeRefreshToken() {
        localStorage.removeItem(REFRESH_TOKEN_KEY)
    },
    saveSession(session){
        localStorage.setItem(SESSIONS,session);
    },
    getSession() {
        return JSON.parse(localStorage.getItem(SESSIONS));
    },
    removeSession() {
        localStorage.removeItem(SESSIONS)
    },
}
 

export { TokenService }