import store from '../../index';
 
import ApiService from "../../../services/api.service";



// export function fetchLogin() {
//     let state = store.state.login;
//     return post('/api/v1/login', {
//         username: state.username,
//         password: state.password
//     });
// }

// export function fetchSignUp() {
//     let state = store.state.login;
//     return post('/api/v1/sign-up', {
//         username: state.username,
//         password: state.password
//     });
// }

export function fetchReadTasks(projectId) {    
    return ApiService.post('/task/list',{
        proyecto_id : projectId
    });
}

export function fetchMoveTasks(data) {    
    return ApiService.post('/task/move',{
        proyecto_id : data.projectId,
        status      : data.estado,
        tarea_id    : data.tarea_id
    });
}


 

 


 
