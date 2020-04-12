const path = require("path");

module.exports = {
    entry: "./react/index.jsx",
    output: {
        path: path.join(__dirname, "/web/js"),
        filename: "app.js"
    },
    resolve: {
        extensions: ['.js', '.jsx']
    },
    module: {
        rules: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader"
                },
            }
        ]
    },
    externals: {
        // global app config object
        config: JSON.stringify({
            apiUrl: 'http://reactyii.loc/api'
        })
    }
};