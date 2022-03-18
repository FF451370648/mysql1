const gis = {
    state: {
        tileInfo: [],
        yzt_location: false,
        base_location: true,
        dynamics: [],
        xz_layer: []
    },
    getters: {
        tileInfo: state => state.tileInfo,
        dynamics: state => state.dynamics,
        yzt_location: state => state.yzt_location,
        base_location: state => state.base_location,
        xz_layer: state => state.xz_layer,
    },
    mutations: {
        SET_TILEINFO: (state, tiles) => {
            //切片图层信息
            state.tileInfo = tiles
            //设置一张图顶部权限
            let yzts = tiles.find(tile => {
                return tile.tile_role === 'yzt'
            })
            if (yzts != null) state.yzt_location = true
        },
        SET_DYNAMIC: (state, dynamics) => {
            state.dynamics = dynamics
        },
        SET_XZ_LAYER: (state, xz_layer) => {
            state.xz_layer = xz_layer
        }
    }
}
export default gis