use std::sync::Arc;
use std::sync::atomic::AtomicBool;

use petgraph::{Graph, Undirected};
use petgraph::algo::min_spanning_tree;
use petgraph::data::FromElements;
use petgraph::graph::{NodeIndex, UnGraph};
use petgraph::graph::node_index as n;
use petgraph::visit::Dfs;

use crate::tsp::{ResolverTSP, TSPMatrix, TSPSolution};

pub struct ChristofidesSolver;


impl ChristofidesSolver {
    fn add_odd_edges(mst: &mut UnGraph<usize, usize>, matrix: &TSPMatrix) {
        let odd: Vec<NodeIndex> = mst.node_indices()
            .filter(|p| mst.edges(p.clone()).count() % 2 == 1)
            .collect();

        let mut distances = vec![];
        for x in odd.iter() {
            for y in odd.iter() {
                if x == y {
                    continue;
                }
                let distance = matrix.distance_between(x.index(), y.index());

                if distances.contains(&(y, x, distance)) {
                    continue;
                }
                let value = (x, y, distance);
                distances.push(value);
            }
        }

        distances.sort_by(|a, b| { return Ord::cmp(&a.2, &b.2); });
        let mut sorted = distances.iter();
        for _ in 0..(odd.len() / 2) {
            let val = sorted.next().unwrap();
            mst.add_edge(*val.0, *val.1, val.2);
        }
    }
}

impl ResolverTSP for ChristofidesSolver {
    fn resolve(matrix: TSPMatrix, _: Arc<AtomicBool>) -> TSPSolution {
        // Convert the matrix to a graph
        let g = <TSPMatrix as Into<Graph<usize, usize, Undirected>>>::into(matrix.clone());
        // Get the MST
        let mut mst = UnGraph::<_, _>::from_elements(min_spanning_tree(&g));
        // Create the the paths between odd edges
        Self::add_odd_edges(&mut mst, &matrix);
        // Deep search first to build the solution
        let mut solution = vec![];
        let mut dfs = Dfs::new(&mst, n(0));

        while let Some(visited) = dfs.next(&mst) {
            // Ignore visited cities
            if !solution.contains(&visited.index()) {
                solution.push(visited.index())
            }
        }
        matrix.build_solution(solution)
    }
}